<?php

namespace Eifl\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username','text',array(
                'label'=>'UserName'
            ))
            ->add('password','password', array(
                'label'=>'Password'
            ))
            ->add('firstname','text',array(
                'label'=>'First Name'
            ))
            ->add('lastname','text',array(
                'label'=>'Last Name',
                'required'=>false,
            ))
            ->add('address','textarea',array(
                'label'=>'Address',
                'attr'=>array(
                    'cols'=>'50',
                    'rows'=>'5',
                    'class'=>'no-resize'
                )
            ))
            ->add('phone','text',array(
                'label'=>'Phone Number'
            ))
            ->add('save','submit', array(
                'label'=>'Create',
            ));
    }

    public function getName()
    {
        return 'UserMember';
    }
}