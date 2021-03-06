<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction;
use AppBundle\Entity\User;
use phpDocumentor\Reflection\Types\Array_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
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
            $user->setName($accounts[0]->getAccountHolderName());
            $user->setNic($accounts[0]->getNic());
            $user->setUserId($userId);
            $user->setDeviceId($IMEI);
            $user->setPhoneNumber($phoneNumber);
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

    /**
     * @Route(path="/registrationService", name="registration_service")
     */
    public function registrationServiceAction(Request $request){
        $logger = $this->get('logger');
        $content = $request->getContent();
        $logger->debug($content);
        $bankManager = $this->get('app.bank_manager');

        $decodedContent = json_decode($content, true);//convert to array
        $logger->debug($decodedContent['nic'].' '.$decodedContent['accountNumber'].' '.$decodedContent['phoneNumber'].' '.$decodedContent['imei']);

        $em = $this->getDoctrine()->getManager();
        $manager = $this->container->get('fos_user.user_manager');

        $user = new User();
        $user->setNic($decodedContent['nic']);

        $userId = md5(uniqid($user->getNic(), true));
        $user->setUsername($userId)
            ->setEmail($userId.'@slipless.lk')// create unique mock email
            ->setPlainPassword('password');


        $user->setDeviceId($decodedContent['imei']);
        $user->setPhoneNumber($decodedContent['phoneNumber']);
        //to avoid integrity constraint violations
        $user->setName($decodedContent['nic']);
        $user->setEnabled(false);
        $user->setUserId($userId);
        try{
            $em->persist($user);
//            $em->persist($account);
            $em->flush();
//            $userAccount1 = array("accountNumber"=>$decodedContent['accountNumber'],
//                "accountHolderName"=>"Asela Priyadarshana",
//                "accountType"=>"Savings");
//            $userAccount2 = array("accountNumber"=> "09909909",
//                "accountHolderName"=>"Asela Priyadarshana",
//                "accountType"=>"Savings");
            $userAccounts = $bankManager->getUserAccounts($decodedContent['nic'], $decodedContent['phoneNumber'],$decodedContent['accountNumber']);
            $userAccountForJson = array();
            foreach ($userAccounts as $userAccount){
                $tempUserAccount = array("accountNumber"=>$userAccount->getAccountNumber(),
                "accountHolderName"=>$userAccount->getAccountHolderName(),
                "accountType"=>$userAccount->getAccountType());

                array_push($userAccountForJson, $tempUserAccount);
            }

            $response = array("status"=>"success", 'data'=>array("userId"=>$user->getUserId(), "accounts"=>$userAccountForJson));
        }catch (Exception $e){
            $response = array("status"=>"fail", 'data'=>null);
        }

        return new Response(json_encode($response));
    }
}
