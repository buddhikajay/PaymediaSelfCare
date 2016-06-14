<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction;
use AppBundle\Entity\User;
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
        $user = $em->getRepository('AppBundle:User')[0];
        $transactionType = $transaction->getType();
        $notesCount =array(5000=>0,2000=>0,1000=>0,500=>0,100=>0,50=>0,20=>0,10=>0,1=>0);
        $notesArray = [5000, 2000, 1000, 500, 100, 50, 20, 10,1];
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

        return $this->render('@App/viewTransaction.html.twig', array("Transaction"=>$transaction
        ,"NotesCount"=>$notesCount,"Notes"=>$notesArray,"User"=>$user));
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
}
