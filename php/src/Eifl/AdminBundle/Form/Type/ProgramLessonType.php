<?php

namespace Eifl\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProgramLessonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("program","entity", array(
                "class"=>'EiflAdminBundle:Level',
                "group_by"=>'program.program',
                "property"=>'level',
                "empty_value"=>'Choose a Level'
            ))
            ->add("lessons","collection", array(
                'type'=> new LessonType(),
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference' => false,
                'required'=>false,
            ))
            ->add("save","submit",array(
                'label'=>'Save',
            ));
    }

    public function getName()
    {
        return 'ClassLevel';
    }
}