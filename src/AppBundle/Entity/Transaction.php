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
     *
     * @ORM\Column(name="referenceNumber", type="string", length=255)
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
     * @ORM\Column(name="branch", type="string", length=255)
     */
    private $branch;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="string", length=255)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="amount2", type="string", length=255)
     */
    private $amount2;

    /**
     * @var float
     *
     * @ORM\Column(name="amount3", type="float")
     */
    private $amount3;

    /**
     * @var string
     *
     * @ORM\Column(name="amountDescription", type="string", length=500)
     */
    private $amountDescription;

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
     * @ORM\Column(name="state", type="integer")
     */
    private $state;


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
     * Set amount2
     *
     * @param string $amount2
     *
     * @return Transaction
     */
    public function setAmount2($amount2)
    {
        $this->amount2 = $amount2;

        return $this;
    }

    /**
     * Get amount2
     *
     * @return string
     */
    public function getAmount2()
    {
        return $this->amount2;
    }

    /**
     * Set amount3
     *
     * @param float $amount3
     *
     * @return Transaction
     */
    public function setAmount3($amount3)
    {
        $this->amount3 = $amount3;

        return $this;
    }

    /**
     * Get amount3
     *
     * @return float
     */
    public function getAmount3()
    {
        return $this->amount3;
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
     * Set state
     *
     * @param integer $state
     *
     * @return Transaction
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }
}

