<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User
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
     * @ORM\Column(name="userId", type="string", length=25, unique=true)
     */
    private $userId;
    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=25)
     */
    private $phoneNumber;
    /**
     * @var string
     *
     * @ORM\Column(name="deviceId", type="string", length=25)
     */
    private $deviceId;
    /**
     * @var string
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @return string
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param string $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Account")
     * @ORM\JoinTable(name="users_ownAccounts",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="account_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $ownAccounts;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Account")
     * @ORM\JoinTable(name="users_thirdPartyAccounts",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="account_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $thirdPartyAccounts;

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
        $this->ownAccounts = new ArrayCollection();
        $this->thirdPartyAccounts = new ArrayCollection();
        $this->transactions = new ArrayCollection();
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
     * Add thirdPartyAccount
     *
     * @param \AppBundle\Entity\Account $thirdPartyAccount
     *
     * @return User
     */
    public function addThirdPartyAccount(\AppBundle\Entity\Account $thirdPartyAccount)
    {
        $this->thirdPartyAccounts[] = $thirdPartyAccount;

        return $this;
    }

    /**
     * Remove thirdPartyAccount
     *
     * @param \AppBundle\Entity\Account $thirdPartyAccount
     */
    public function removeThirdPartyAccount(\AppBundle\Entity\Account $thirdPartyAccount)
    {
        $this->thirdPartyAccounts->removeElement($thirdPartyAccount);
    }

    /**
     * Get thirdPartyAccounts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getThirdPartyAccounts()
    {
        return $this->thirdPartyAccounts;
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
