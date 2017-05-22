<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\Type\TransactionType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class TransactionController extends Controller
{

    /**
     * @Route("/transaction/get", name="transaction_get")
     */
    public function getTransaction(Request $request)
    {
        $logger = $this->get('logger');
//        $em = $this->getDoctrine()->getRepository('AppBundle:Transaction');
        $em = $this->getDoctrine()->getManager();
        $refNo = $request->query->get('refNo');
        $transaction = $em->getRepository('AppBundle:Transaction')->findOneByReferenceNumber($refNo);
        $user = $em->getRepository('AppBundle:User')->findAll()[0];
        $transactionType = $transaction->getType();
        $notesCount =array(5000=>0,2000=>0,1000=>0,500=>0,100=>0,50=>0,20=>0,10=>0,1=>0);
        $notesArray = [5000, 2000, 1000, 500, 100, 50, 20, 10,1];
        $amountDescriptionArray=null;//array to store cash denomination or cheque details
        if($transactionType == 'Cash Deposit') {
            $amountDescription = $transaction->getAmountDescription();
            $amountDescriptionArray = json_decode($amountDescription, true);
            foreach($notesArray as $note){
                $stringNoteKey = strval($note);
                if(isset($amountDescriptionArray[$note])){
                    $notesCount[$note]=$amountDescriptionArray[$note];
                }
                else{
                    $notesCount[$note]=0;
                }
            }
//        $logger->debug($amountDescription);
//        $logger->debug($amountDescriptionArray[5000]);
           
        }
//        else if($transactionType=='Cash Withdraw'){
//
//        }
        else if($transactionType=='Cheque Deposit'){
            $amountDescription = $transaction->getAmountDescription();
            $amountDescriptionArray = json_decode($amountDescription, true);
           // $logger->debug("Cheque Data array: ".$amountDescriptionArray);
            if(!is_null($amountDescriptionArray)) {
                foreach ($amountDescriptionArray as $cheque) {
                    //$chequeData = json_decode($cheque);
                    $logger->debug("Cheque Data: " . $cheque['Bank']);
                }
            }
        }

        return $this->render('@App/viewTransaction.html.twig', array("Transaction"=>$transaction
        ,"NotesCount"=>$notesCount,"Notes"=>$notesArray,"user"=>$user,"Cheques"=>$amountDescriptionArray));
    }

    /**
     * @Route("/transaction/proceed", name="transaction_proceed")
     */
    public function proceedTransaction(Request $request)
    {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();
        $content = $request->getContent();
        $logger->debug("Content: ".$content);

        $decodedContent = json_decode($content);


        $refNo = $decodedContent->refNo;
       // $refNo = $request->query->get('refNo');

        $branch =$decodedContent->branch;
        $transaction = $em->getRepository('AppBundle:Transaction')->findOneByReferenceNumber($refNo);
        $transaction->setStatus(1);
        $transaction->setUpdated(true);
        $transaction->setBranch($branch);
        $transaction->setCompletedAt(new \DateTime('now'));
//            $em->persist($transaction);
        $em->flush();
        return new Response(json_encode(array("code"=>200)));
    }

    /**
     * @Route("/slip/deposit/cash", name="cash_deposit_slip")
     */
    public function cashDepositSlipAction(Request $request){
        $response = array(
            'response' => 'create_successful',
            'ref_no' => '12344321'
        );
        return new JsonResponse($response);
    }
//create transaction action for new app
    /**
     * @Route("/transaction/new", name="create_new_transaction")
     */
    public function createTransactionAction(Request $request){
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');
        $request =  $this->container->get('request_stack')->getCurrentRequest();

        $userRepository = $em->getRepository('AppBundle:User');
        $accountRepository = $em->getRepository('AppBundle:Account');
        $logger->debug($request);

        $username = $request->request->get('user');
        $requestData =$request->request->get('data');
        $data = json_decode($requestData, true);

        $type = $data['type'];
        $amount = $data['amount'];
        $accountNo = $data['account_no'];

        $user = $userRepository->findOneByUserId($username);
        $account  = $accountRepository->findOneByAccountNumber($accountNo);

        if(!is_null($account)){
            $accountName = $account->getAccountHolderName();
        }

        else{
            $accountName = $data['account_name'];
            $account = new Account();
            $account->setAccountNumber($accountNo);
            $account->setAccountHolderName($accountName);

        }

        if( strcmp ($type,'Cash Withdraw')==0){

            $amountDescription=null;
            $sourceOfFunds = null;

        }
        else if(strcmp ($type,'Fund Transfer')==0){
            $amountDescription=null;
            $sourceOfFunds = null;
        }
        else{

            $amountDescription = $data['amount_description'];
            $sourceOfFunds = $data['source_of_funds'];
        }


        $em->persist($account);

        $transaction = new Transaction();
//        TODO auto generate | unique
//        $transaction->setReferenceNumber(1);
        $transaction->setAccount($account);
        $transaction->setBranch("Bambalapitiya");
        $transaction->setAmount((float)$amount);
        $transaction->setAmountDescription($amountDescription);
        $transaction->setSourceOfFunds($sourceOfFunds);
        $transaction->setType($type);
        $transaction->setStatus("Pending");

        $user->addTransaction($transaction);

        $em->persist($user);
        $em->persist($transaction);

        $em->flush();


        if( strcmp ($type,'Fund Transfer')==0){

            $userId = $data['receiver_Id'];
            $user = $userRepository->findOneByUserId($userId);
            $transaction = new Transaction();
//        TODO auto generate | unique
//        $transaction->setReferenceNumber(1);
            $transaction->setAccount($account);
            $transaction->setBranch("Bambalapitiya");
            $transaction->setAmount((float)$amount);
            $transaction->setAmountDescription($amountDescription);
            $transaction->setSourceOfFunds($sourceOfFunds);
            $transaction->setType($type);
            $transaction->setStatus("Completed");
            $transaction->setUpdated(true);
            $transaction->setCompletedAt(new \DateTime());
            $user->addTransaction($transaction);

            $em->persist($user);
            $em->persist($transaction);

            $em->flush();
        }


        $response = array(
        'response' => 'create_successful',
        'ref_no' => $transaction->getReferenceNumber()
        );
        return new JsonResponse($response);
//        return new Response(json_encode(array('status'=>200, 'refNo'=>$transaction->getReferenceNumber())));
    }


//    get updates action for new app
    /**
     * @Route("/transaction/updates", name="transaction_updates")
     */
    public function getUpdates(){
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Transaction');
        $userRepository = $em->getRepository('AppBundle:User');

        $updates = array();

        $request =  $this->container->get('request_stack')->getCurrentRequest();
        $logger->debug($request);
        $username = $request->request->get('user');
//        $requestData =$request->request->get('data');
//        $data = json_decode($requestData, true);


        $user = $userRepository->findOneByUserId($username);
        $transactions = $user->getTransactions();

        foreach($transactions as $transaction){
            if($transaction->isPinRequested()){
                $transaction->setPinRequested(false);
                $em->persist($transaction);
                $em->flush();
                $response = array(
                    'response' => 'pin_request',
                    'ref_no' => $transaction->getReferenceNumber(),

                );
                return new JsonResponse($response);
            }
            if($transaction->isUpdated()){
                $transaction->setUpdated(false);
                $em->persist($transaction);
                $em->flush();
                if( strcmp ($transaction->getType(),'Fund Transfer')==0){
                    $response = array(
                        'response' => 'fund_transfer',
                        'amount' => $transaction->getAmount(),
                        'from' => $transaction->getAccount()->getAccountHolderName(),
                        'completed_at' => $transaction->getCompletedAt()->format('U')
                    );
                }
                else {
                    $response = array(
                        'response' => 'updated',
                        'ref_no' => $transaction->getReferenceNumber(),
                        'branch' => $transaction->getBranch(),
                        'completed_at' => $transaction->getCompletedAt()->format('U')
                    );
                }
                return new JsonResponse($response);
            }
        }
        $response = array(
            'response' => 'no_change'
        );
        return new JsonResponse($response);
//        For prototype, only one transaction can be handled at a given time
//        $pinRequestedTransaction= $repository->createQueryBuilder('transaction')
//            ->where('transaction.pinRequested = 1')
//            ->getQuery()
//            ->getResult();
//        if($pinRequestedTransaction){
//            foreach($pinRequestedTransaction as $transaction){
//                $transaction->setpinRequested(false);
//                $em->flush();
//                $tempUpdate = array(
//                    'refNo'=>'pin',
//                    'branch'=>null,
//                    'dateTime'=> null);
//                array_push($updates, $tempUpdate);
//            }
//            return new Response(json_encode($updates));
//        }
//
//        $query = $repository->createQueryBuilder('t')
//            ->where('t.status = :status')
//            ->setParameter('status', '1')
//            ->getQuery();
//        $updatedTransactions = $query->getResult();
//
//        foreach($updatedTransactions as $transaction){
//            $dateTime = new \DateTime();
////            $logger->debug($dateTime);
//            $tempUpdate = array(
//                'refNo'=>$transaction->getReferenceNumber(),
//                'branch'=>$transaction->getBranch(),
//                'dateTime'=>strval($dateTime->getTimestamp()));
//            array_push($updates, $tempUpdate);
//            $transaction->setStatus(2);
//            $em->flush();
//        }
//
////        $dateTime = new \DateTime();
////        $updates = array('refNo'=>'574aeda7a8cd2', 'branch'=>'Dubai', 'dateTime'=>$dateTime->getTimestamp());
//        return new Response(json_encode($updates));
    }

}
