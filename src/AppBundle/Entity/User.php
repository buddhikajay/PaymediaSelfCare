<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="userId", type="string", length=25, unique=true)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=25)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="nic", type="string", length=25)
     */
    private $nic;

    private $phoneNumber;
    /**
     * @var string
     *
     * @ORM\Column(name="deviceId", type="string", length=25)
     */
    private $deviceId;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Account")
     * @ORM\JoinTable(name="users_ownAccounts",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="account_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $ownAccounts;


    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Transaction")
     * @ORM\JoinTable(name="users_transactions",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="transaction_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $transactions;

    public function __construct()
    {
        parent::__construct();
        $this->ownAccounts = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->enabled=false;
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return string
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * @param string $deviceId
     */
    public function setDeviceId($deviceId)
    {
        $this->deviceId = $deviceId;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
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
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Set ownAccounts
     *
     * @param string $ownAccounts
     *
     * @return User
     */
    public function setOwnAccounts($ownAccounts)
    {
        $this->ownAccounts = $ownAccounts;

        return $this;
    }

    /**
     * Get ownAccounts
     *
     * @return string
     */
    public function getOwnAccounts()
    {
        return $this->ownAccounts;
    }

    /**
     * Add ownAccount
     *
     * @param \AppBundle\Entity\Account $ownAccount
     *
     * @return User
     */
    public function addOwnAccount(\AppBundle\Entity\Account $ownAccount)
    {
        $this->ownAccounts[] = $ownAccount;

        return $this;
    }

    /**
     * Remove ownAccount
     *
     * @param \AppBundle\Entity\Account $ownAccount
     */
    public function removeOwnAccount(\AppBundle\Entity\Account $ownAccount)
    {
        $this->ownAccounts->removeElement($ownAccount);
    }

    
    /**
     * Add transaction
     *
     * @param \AppBundle\Entity\Transaction $transaction
     *
     * @return User
     */
    public function addTransaction(\AppBundle\Entity\Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * Remove transaction
     *
     * @param \AppBundle\Entity\Transaction $transaction
     */
    public function removeTransaction(\AppBundle\Entity\Transaction $transaction)
    {
        $this->transactions->removeElement($transaction);
    }

    /**
     * Get transactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }
}
