<?php

namespace Eifl\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StudentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('userMember','entity',array(
            'class'=>'EiflAdminBundle:Member',
            'property'=>'userMember.username',
            'multiple'=>true,
            'required'=>true,
            'expanded' => true,
            ))
            ->add('save','submit', array(
                'label'=>'Save'
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eifl\AdminBundle\Entity\Member',
        ));
    }

    public function getName()
    {
        return 'students';
    }
}