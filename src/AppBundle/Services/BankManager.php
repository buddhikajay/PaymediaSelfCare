<?php

namespace AppBundle\Services;
use AppBundle\Entity\Account;
use Doctrine\ORM\EntityManager;

/**
 * Created by PhpStorm.
 * User: buddhikajay
 * Date: 12/5/16
 * Time: 8:59 AM
 */
class BankManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    private $initials = array("A.B.C.", "K.L.D.", "G.J.L.", "D.M.L.", "E.I.P.");
    private $names = array("Gunadasa", "Amaraweera", "Jayaweera", "Padmanadan", "Godakanda", "Dinesh", "Charuk", "Unambuwa", "Gunasekara", "Mahabage");
    private $accountTypes = array("Savings", "Current");


    public function __construct(EntityManager $entityManager)
    {
//        var_dump($entityManager);
        $this->em = $entityManager;
    }

    public function validateAccount($accountNumber){
        $account = array("accountNumber"=>$accountNumber,
            "accountHolderName"=>$this->initials[rand(0,4)].$this->names[rand(0,9)],
            "accountType"=>$this->accountTypes[rand(0,1)]);
        return $account;
    }

    /**
     * @param $nic
     * @param $phoneNumber
     * @param $accountNumber
     * @return array
     */
    public function getUserAccounts($nic, $phoneNumber, $accountNumber){

        //defaul account
        $defaultAccount = new Account();
        $accountsObjectArray = array();
//        $accountsArray = arrry();

        $randomAccountHolderName = $this->initials[rand(0,4)].$this->names[rand(0,9)];

        $defaultAccount->setAccountNumber($accountNumber);
        $defaultAccount->setNic($nic);
        $defaultAccount->setPhoneNumber($phoneNumber);
        $defaultAccount->setAccountType($this->accountTypes[rand(0,1)]);
        $defaultAccount->setAccountHolderName($randomAccountHolderName);

        array_push($accountsObjectArray, $defaultAccount);
        $this->em->persist($defaultAccount);

        for ($i = 0; $i<2;$i++){

            $randomAccountNumber = '';
            for($j=0;$j<7;$j++){
                $randomAccountNumber .= rand(0,9);
            }
            $randomAccountType = $this->accountTypes[rand(0,1)];

            $tempAccountObject = new Account();
            $tempAccountObject->setAccountNumber($randomAccountNumber);
            $tempAccountObject->setAccountType($randomAccountType);
            $tempAccountObject->setAccountHolderName($randomAccountHolderName);

            $tempAccountObject->setNic($nic);
            $tempAccountObject->setPhoneNumber($phoneNumber);

//            $tempAccount = array("accountNumber"=>$randomAccountNumber,
//                "accountHolderName"=>$randomAccountHolderName,
//                "accountType"=>$randomAccountType);

//            array_push($accountsArray, $tempAccount);
            $this->em->persist($tempAccountObject);
            array_push($accountsObjectArray, $tempAccountObject);
        }

        //save account
        $this->em->flush();
        return $accountsObjectArray;
    }
}