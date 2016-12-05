<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction;
use AppBundle\Entity\User;
use phpDocumentor\Reflection\Types\String_;
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

class AccountController extends Controller
{

    /**
     * @Route("/account/getName", name="account_get_name")
     */
    public function getAccountHolderName(Request $request)
    {
        $logger = $this->get('logger');
        $content = $request->getContent();
        $logger->debug("Content: ".$content);

        $decodedContent = json_decode($content);

        $accountNumber = $decodedContent->AccountNumber;
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Account');
        $query = $repository->createQueryBuilder('t')
            ->where('t.accountNumber = :accountNumber')
            ->setParameter('accountNumber', $accountNumber)
            ->getQuery();
        $accounts = $query->getResult();
      
        if($accounts){
            return new Response(json_encode(array("code"=>200,"name"=>$accounts[0]->getAccountHolderName())));
        }
        else{
            return new Response(json_encode(array("code"=>400,"name"=>null)));
        }
    }

    /**
     * @Route("/account/findName", name="account_find_name")
     */
    public function findAccountHolderName(Request $request)
    {
        $logger = $this->get('logger');
        $request =  $this->container->get('request_stack')->getCurrentRequest();

        $em = $this->getDoctrine()->getManager();
        $accountRepository = $em->getRepository('AppBundle:Account');
        $logger->debug($request);

        $username = $request->request->get('user');
        $requestData =$request->request->get('data');
        $data = json_decode($requestData, true);


        $accountNumber =$data['account_no'];

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Account');
        $query = $repository->createQueryBuilder('t')
            ->where('t.accountNumber = :accountNumber')
            ->setParameter('accountNumber', $accountNumber)
            ->getQuery();
        $accounts = $query->getResult();

        if($accounts){

            $response = array(
                'response' => 'account_name_valid',
                'account_name' =>$accounts[0]->getAccountHolderName()
            );
            return new JsonResponse($response);
        }
        else{
            $response = array(
                'response' => 'account_name_invalid',

            );
            return new JsonResponse($response);
        }
    }

    /**
     * @param Request $request
     * @Route(path="/service/account/create", name="create_account_service")
     */
    public function createAccountService(Request $request){
        $logger = $this->get('logger');
        $bankManager = $this->get('app.bank_manager');
        $content = $request->getContent();

        $logger->debug($content);
        $decodedContent = json_decode($content, true);//convert to array
        $logger->debug($decodedContent['accountNumber']);

        $createdAccount = $bankManager->validateAccount($decodedContent['accountNumber']);

        $response = array("status"=>"success", "data"=>array("account"=>$createdAccount));

        return new Response(json_encode($response));

    }
}