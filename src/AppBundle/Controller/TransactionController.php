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
        $transactionType = $transaction->getType();
        $notesCount =array();
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
        ,"NotesCount"=>$notesCount,"Notes"=>$notesArray));
    }
}
