<?php

namespace Eifl\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewClassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("class","text", array(
                'label'=>'Class Name',
            ));
    }

    public function getName()
    {
        return 'NewClass';
    }
}