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
            foreach($amountDescriptionArray as $cheque){
                //$chequeData = json_decode($cheque);
                $logger->debug("Cheque Data: ".$cheque['Bank']);
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
        $transaction->setBranch($branch);
        $transaction->setCompletedAt(new \DateTime('now'));
//            $em->persist($transaction);
        $em->flush();
        return new Response(json_encode(array("code"=>200)));
    }

    /**
     * @Route("/transaction/new", name="create_new_transaction")
     */
    public function createTransaction(Request $request){
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');
        $request =  $this->container->get('request_stack')->getCurrentRequest();
        $logger->debug($request);
        $username = $request->request->get('user');
        $requestData =$request->request->get('data');
        $data = json_decode($requestData, true);

        $type = $data['type'];
        $amount = $data['amount'];
        $accountNo = $data['account_no'];

        if( strcmp ($type,'Cash Withdraw')==0){

            $accountName = "null";

            $amountDescription=null;
            $sourceOfFunds = null;

        }
        else{
            $accountName = $data['account_name'];

            $amountDescription = $data['amount_description'];
            $sourceOfFunds = $data['source_of_funds'];

        }


        $account = new Account();
        $account->setAccountNumber($accountNo);
        $account->setAccountHolderName($accountName);

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

        $em->persist($transaction);

        $em->flush();

//        TODO this try catch can be used to avoid duplicates for the unique values
//
//        try {
//            $em->persist($data);
//            $em->flush();
//        } catch(\PDOException $e) {
//            // handle exception
//        }

        $response = array(
            'success' => true,
            'ref_no' => $transaction->getReferenceNumber()
        );
        return new JsonResponse($response);
//        return new Response(json_encode(array('status'=>200, 'refNo'=>$transaction->getReferenceNumber())));
    }

}
