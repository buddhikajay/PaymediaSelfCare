<?php
/**
 * Created by PhpStorm.
 * User: buddhikajay
 * Date: 5/29/16
 * Time: 11:18 AM
 */

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('referenceNumber')
//            ->add('account', EntityType::class, array(
//                'class'=>'AppBundle:Account',
//                'choice_label' => 'id'
//            ))
            ->add('account', AccountType::class)
            ->add('branch')
            ->add('type')
            ->add('amount')

//            ->add('10', null, array('mapped' => false))
//            ->add('20', null, array('mapped' => false))
//            ->add('50', null, array('mapped' => false))
//            ->add('100', null, array('mapped' => false))
//            ->add('500', null, array('mapped' => false))
//            ->add('1000', null, array('mapped' => false))
//            ->add('2000', null, array('mapped' => false))
//            ->add('5000', null, array('mapped' => false))

//            ->add('sourceOfFunds')
//            ->add('createdAt')

//            ->add('save', SubmitType::class, array('label' => 'Proceed'))
        ;
    }
}