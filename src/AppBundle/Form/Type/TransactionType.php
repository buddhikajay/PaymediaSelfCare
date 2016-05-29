<?php
/**
 * Created by PhpStorm.
 * User: buddhikajay
 * Date: 5/29/16
 * Time: 11:18 AM
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('referenceNumber')
//            ->add('account', DateType::class)
            ->add('branch', null, array('data'=>'Dubai'))
            ->add('amount')
            ->add('100', null, array('mapped' => false))
            ->add('save', SubmitType::class, array('label' => 'Proceed'))
        ;
    }
}