<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\Type\TransactionType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/transaction/create", name="create_transaction")
     */
    public function createTransaction(Request $request){
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();
        $content = $request->getContent();
//        $logger->debug("Request JSON");
        $logger->debug($content);
        $decodedContent = json_decode($content);
        $logger->debug($decodedContent->Type);

        $account = new Account();
        $account->setAccountNumber($decodedContent->Account->AccountNumber);
        $account->setAccountHolderName($decodedContent->Account->AccountHolderName);

        $em->persist($account);

        $transaction = new Transaction();
//        TODO auto generate | unique
//        $transaction->setReferenceNumber(1);
        $transaction->setAccount($account);
        $transaction->setBranch("Dubai");
        $transaction->setAmount((float)$decodedContent->Amount);
        $transaction->setAmountDescription($decodedContent->AmountDescription);
        $transaction->setSourceOfFunds($decodedContent->SourceOfFunds);
        $transaction->setType($decodedContent->Type);
        $transaction->setStatus($decodedContent->Status);

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

        return new Response(json_encode(array('status'=>200, 'refNo'=>$transaction->getReferenceNumber())));
    }

    /**
     * @Route("/transaction/scanner", name="transaction_scanner")
     */
    public function getCodeScanner(Request $request){
        $logger = $this->get('logger');
        $refNo= $request->query->get('refNo', null);
        $logger->debug($request->query->get('refNo', $refNo));
        if(is_null($refNo)){
            return $this->render('@App/scanner.html.twig');
        }
        else{
            return $this->redirect($this->generateUrl('transaction_get', array('refNo'=>$refNo)));
        }
    }

    /**
     * @Route("/transaction/get", name="transaction_get")
     */
    public function getTransaction(Request $request){

        $logger = $this->get('logger');
//        $em = $this->getDoctrine()->getRepository('AppBundle:Transaction');
        $em = $this->getDoctrine()->getManager();
        $refNo = $request->query->get('refNo');
        $transaction = $em->getRepository('AppBundle:Transaction')->findOneByReferenceNumber($refNo);//5749cd08d18aa
        $form = $this->createForm(TransactionType::class, $transaction);

        $transactionType = $transaction->getType();

        $logger->debug($transactionType);
        if($transactionType == 'Cash Deposit'){
            $amountDescription = $transaction->getAmountDescription();
            $amountDescriptionArray = json_decode($amountDescription, true);

//        $logger->debug($amountDescription);
//        $logger->debug($amountDescriptionArray[5000]);
            $notesArray = [10, 20, 50, 100, 500, 1000, 2000, 5000];
            foreach($notesArray as $note){
                $stringNoteKey = strval($note);
                if(isset($amountDescriptionArray[$note])){
                    $form->add($stringNoteKey, null, array('mapped' => false, 'data'=>$amountDescriptionArray[$note]));
//                $form->get($stringNoteKey)->setData($amountDescriptionArray[$note]);
//                $logger->debug($amountDescriptionArray[$note]);
                }
//            else{
//                $form->get($stringNoteKey)->setData('0');
//            }
            }
            //add source of funds description to the form if it is available
            $sourceOfFunds = $transaction->getSourceOfFunds();
            if(!is_null($sourceOfFunds)){
                $form->add('sourceOfFunds');
            }
        }

        $form->add('save', SubmitType::class, array('label' => 'Proceed'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transaction->setStatus(1);
            $transaction->setCompletedAt(new \DateTime('now'));
//            $em->persist($transaction);
            $em->flush();
            return $this->render('@App/completed.html.twig');
        }

        return $this->render('@App/review.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/transaction/getUpdates", name="transaction_get_updates")
     */
    public function getUpdates(){
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Transaction');
        $query = $repository->createQueryBuilder('t')
            ->where('t.status = :status')
            ->setParameter('status', '1')
            ->getQuery();
        $updatedTransactions = $query->getResult();

        $updates = array();
        foreach($updatedTransactions as $transaction){
            $dateTime = new \DateTime();
//            $logger->debug($dateTime);
            $tempUpdate = array(
                'refNo'=>$transaction->getReferenceNumber(),
                'branch'=>$transaction->getBranch(),
                'dateTime'=>strval($dateTime->getTimestamp()));
            array_push($updates, $tempUpdate);
            $transaction->setStatus(2);
            $em->flush();
        }

//        $dateTime = new \DateTime();
//        $updates = array('refNo'=>'574aeda7a8cd2', 'branch'=>'Dubai', 'dateTime'=>$dateTime->getTimestamp());
        return new Response(json_encode($updates));
    }
}
