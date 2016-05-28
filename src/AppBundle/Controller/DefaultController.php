<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

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
//        $logger->debug($content);
        $decodedContent = json_decode($content);
        $logger->debug($decodedContent->Type);

        $account = new Account();
        $account->setAccountNumber($decodedContent->Account->AccountNumber);
        $account->setAccountHolderName($decodedContent->Account->AccountHolderName);

        $em->persist($account);

        $transaction = new Transaction();
//        TODO auto generate | unique
//        $transaction->setReferenceNumber(1);
        $transaction->setType($decodedContent->Type);
        $transaction->setAmount((float)$decodedContent->Amount);
        $transaction->setAmountDescription($decodedContent->AmountDescription);
        $transaction->setStatus($decodedContent->Status);
        $transaction->setAccount($account);

        $em->persist($transaction);

        $em->flush();
//
//        try {
//            $em->persist($data);
//            $em->flush();
//        } catch(\PDOException $e) {
//            // handle exception
//        }

        return new Response(json_encode(array('status'=>200, 'refNo'=>$transaction->getReferenceNumber())));
    }
}
