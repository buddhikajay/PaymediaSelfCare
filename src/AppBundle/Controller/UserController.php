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

class UserController extends Controller
{

    /**
     * @Route("/user/requestRegistration", name="user_request_registration")
     */
    public function requestRegistration(Request $request){
        $logger = $this->get('logger');
        $content = $request->getContent();
        $logger->debug("Content: ".$content);

        $decodedContent = json_decode($content);
         
        $accountNumber = $decodedContent->AccountNumber;
        $phoneNumber = $decodedContent->PhoneNumber;
        $NIC = $decodedContent->NIC;
        $IMEI=$decodedContent->IMEI;
//        $accountNumber = "12345678";
//        $phoneNumber = "0712345678";
//        $NIC = "790000000v";
//        $IMEI="1234567891234567";
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Account');
        $query = $repository->createQueryBuilder('t')
            ->where('t.accountNumber = :accountNumber')
            ->setParameter('accountNumber', $accountNumber)
        ->andWhere( 't.nic = :nic')
            ->setParameter('nic',$NIC)
        ->andWhere ('t.phoneNumber =:phoneNumber')
            ->setParameter('phoneNumber', $phoneNumber)
            ->getQuery();
        $accounts = $query->getResult();
       if($accounts){
            $user  = new User();
            foreach($accounts as $account){
                $user->addOwnAccount($account);
            }
            $userId= uniqid();
            $user->setUserId($userId);
            $em->persist($user);
            $em->flush();
            $response =$userId ;
        }
        else{
            $response = "Invalid";
        }

        $logger->debug("Response: ".$response);
 
      
        //$test = array("AccountNumber"=>$decodedContent->AccountNumber);

        return new Response(json_encode($response));
    }
    /**
     * @Route("/user/verifyConfirmationCode", name="user_verify_confirmation_code")
     */
    public function verifyConfirmationCode(Request $request){
        
    }
}