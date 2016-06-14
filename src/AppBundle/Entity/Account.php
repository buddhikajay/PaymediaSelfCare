<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccountRepository")
 */
class Account
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="accountNumber", type="string", length=25, unique=false)
     */
    private $accountNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="accountHolderName", type="string", length=255)
     */
    private $accountHolderName;

    /**
     * @var string
     *
     * @ORM\Column(name="nic", type="string", length=20,nullable=true)
     */
    private $nic;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=25,nullable=true)
     */
    private $phoneNumber;
    /**
     * @var string
     *
     * @ORM\Column(name="accountType", type="string", length=25,nullable=true)
     */
    private $accountType;

    /**
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * @param string $accountType
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getNic()
    {
        return $this->nic;
    }

    /**
     * @param string $nic
     */
    public function setNic($nic)
    {
        $this->nic = $nic;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set accountNumber
     *
     * @param string $accountNumber
     *
     * @return Account
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Set accountHolderName
     *
     * @param string $accountHolderName
     *
     * @return Account
     */
    public function setAccountHolderName($accountHolderName)
    {
        $this->accountHolderName = $accountHolderName;

        return $this;
    }

    /**
     * Get accountHolderName
     *
     * @return string
     */
    public function getAccountHolderName()
    {
        return $this->accountHolderName;
    }
}
