<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FundTransferTransaction
 *
 * @ORM\Table(name="fund_transfer_transaction")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FundTransferTransactionRepository")
 */
class FundTransferTransaction extends Transaction
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
     * @ORM\Column(name="text", type="string", length=15, nullable=true)
     */
    private $toAccountNumber;


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
     * Set text
     *
     * @param string $toAccountNumber
     *
     * @return FundTransferTransaction
     */
    public function setToAccountNumber($toAccountNumber)
    {
        $this->toAccountNumber = $toAccountNumber;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getToAccountNumber()
    {
        return $this->toAccountNumber;
    }
}

