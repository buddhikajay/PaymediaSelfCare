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
            return new Response(json_encode(array("code"=>200,"name"=>$accounts[0]->accountHolderName)));
        }
        else{
            return new Response(json_encode(array("code"=>400)));
        }
    }
}