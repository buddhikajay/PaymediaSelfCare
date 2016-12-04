<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransactionRepository")
 */
class Transaction
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
     * @ORM\Column(name="reference_number", type="string", unique=true)
     */
    private $referenceNumber;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Account")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="branch", type="string", length=255, nullable=true)
     */
    private $branch;


    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="amountDescription", type="string", length=50000, nullable=true)
     */
    private $amountDescription;

    /**
     * @var string
     * @ORM\Column(name="source_of_funds", type="string", length=500, nullable=true)
     */
    private $sourceOfFunds;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="completedAt", type="datetime", nullable=true)
     */
    private $completedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pin_requested", type="boolean", nullable=true)
     */
    private $pinRequested = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="updated", type="boolean", nullable=true)
     */
    private $updated = false;

    /**
     * @return boolean
     */
    public function isUpdated()
    {
        return $this->updated;
    }

    /**
     * @param boolean $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @var int
     * @ORM\Column(name="input_pin", type="integer", nullable=true)
     */
    private $inputPin = 0;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->referenceNumber = substr(rand(),0,6);  // creates a 6 digit token
    }

    public function __toString(){
        return $this->referenceNumber;
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
     * Set referenceNumber
     *
     * @param string $referenceNumber
     *
     * @return Transaction
     */
    public function setReferenceNumber($referenceNumber)
    {
        $this->referenceNumber = $referenceNumber;

        return $this;
    }

    /**
     * Get referenceNumber
     *
     * @return string
     */
    public function getReferenceNumber()
    {
        return $this->referenceNumber;
    }

    /**
     * Set account
     *
     * @param string $account
     *
     * @return Transaction
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set branch
     *
     * @param string $branch
     *
     * @return Transaction
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get branch
     *
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }


    /**
     * Set amountDescription
     *
     * @param string $amountDescription
     *
     * @return Transaction
     */
    public function setAmountDescription($amountDescription)
    {
        $this->amountDescription = $amountDescription;

        return $this;
    }

    /**
     * Get amountDescription
     *
     * @return string
     */
    public function getAmountDescription()
    {
        return $this->amountDescription;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Transaction
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set completedAt
     *
     * @param \DateTime $completedAt
     *
     * @return Transaction
     */
    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    /**
     * Get completedAt
     *
     * @return \DateTime
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Transaction
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Transaction
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set sourceOfFunds
     *
     * @param string $sourceOfFunds
     *
     * @return Transaction
     */
    public function setSourceOfFunds($sourceOfFunds)
    {
        $this->sourceOfFunds = $sourceOfFunds;

        return $this;
    }

    /**
     * Get sourceOfFunds
     *
     * @return string
     */
    public function getSourceOfFunds()
    {
        return $this->sourceOfFunds;
    }

    /**
     * Set pinRequested
     *
     * @param boolean $pinRequested
     *
     * @return Transaction
     */
    public function setPinRequested($pinRequested)
    {
        $this->pinRequested = $pinRequested;

        return $this;
    }

    /**
     * Get pinRequested
     *
     * @return boolean
     */
    public function isPinRequested()
    {
        return $this->pinRequested;
    }

    /**
     * Get pinRequested
     *
     * @return boolean
     */
    public function getPinRequested()
    {
        return $this->pinRequested;
    }

    /**
     * Set inputPin
     *
     * @param integer $inputPin
     *
     * @return Transaction
     */
    public function setInputPin($inputPin)
    {
        $this->inputPin = $inputPin;

        return $this;
    }

    /**
     * Get inputPin
     *
     * @return integer
     */
    public function getInputPin()
    {
        return $this->inputPin;
    }

//    public function __toString(){
//        return $this->referenceNumber;
//    }
}
