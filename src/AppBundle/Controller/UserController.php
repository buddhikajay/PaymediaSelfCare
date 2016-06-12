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
        
 
      
        $test = array("AccountNumber"=>$decodedContent->AccountNumber);
        $response = "Invalid";
        return new Response(json_encode($test));
    }
    /**
     * @Route("/user/verifyConfirmationCode", name="user_verify_confirmation_code")
     */
    public function verifyConfirmationCode(Request $request){

    }
}
