<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * Set accounts
     *
     * @param string $accounts
     *
     * @return User
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;

        return $this;
    }

    /**
     * Get accounts
     *
     * @return string
     */
    public function getAccounts()
    {
        return $this->accounts;
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
}

