<?php

namespace Eifl\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username','text',array(
                'label'=>'Admin Name',
            ))
            ->add('password','password', array(
                'label'=>'Password',
            ))
            ->add('roles', 'choice', array(
                'choices'  => array(
                    'ROLE_ADMIN' => 'Principal',
                    'ROLE_TEACHER' => 'Teacher',
                    'ROLE_ADM'    => 'Adm',
                ),
                'label'=>'Position',
                'empty_value' => 'Chooce your position',
            ))
            ->add('enabled', 'choice', array(
                'choices'  => array(
                    '1' => 'Active',
                    '0' => 'Inactive',
                ),
                'label'=>'Status',
                'empty_value' => 'Set your status',
            ))
            ->add('save','submit', array(
                'label'=>'Create',
            ));
    }

    public function getName()
    {
        return 'UserAdmin';
    }
}