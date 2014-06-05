<?php

namespace Eifl\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClassLevelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("level","entity", array(
                "class"=>'EiflAdminBundle:Level',
                "group_by"=>'program.program',
                "property"=>'level',
                "empty_value"=>'Choose a level'
            ))
            ->add("class","collection", array(
                'type'=> new NewClassType(),
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference' => false,
                'required'=>false,
            ))
            ->add("save","submit",array(
                'label'=>'Save',
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eifl\AdminBundle\Entity\ClassLevel',
        ));
    }

    public function getName()
    {
        return 'ClassLevel';
    }
}