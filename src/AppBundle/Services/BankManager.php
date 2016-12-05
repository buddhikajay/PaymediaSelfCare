<?php

namespace AppBundle\Services;

/**
 * Created by PhpStorm.
 * User: buddhikajay
 * Date: 12/5/16
 * Time: 8:59 AM
 */
class BankManager
{
    private $initials = array("A.B.C.", "K.L.D.", "G.J.L.", "D.M.L.", "E.I.P.");
    private $names = array("Gunadasa", "Amaraweera", "Jayaweera", "Padmanadan", "Godakanda");
    private $accountTypes = array("Savings", "Current");


    public function validateAccount($accountNumber){
        $account = array("accountNumber"=>$accountNumber,
            "accountHolderName"=>$this->initials[rand(0,5)].$this->names[rand(0,5)],
            "accountType"=>$this->accountTypes[rand(0,1)]);
        return $account;
    }
}