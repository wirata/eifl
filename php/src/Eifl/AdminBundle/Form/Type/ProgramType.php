<?php

namespace Eifl\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id','text',array(
                'label'=>'Program Code',
            ))
            ->add('program','text',array(
                'label'=>'Program Name',
            ))
            ->add('levels','collection', array(
                'type'=> new LevelType(),
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference' => false,
                'required'=>true,
            ))
            ->add('save','submit', array(
                'label'=>'Save',
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eifl\AdminBundle\Entity\Program',
        ));
    }

    public function getName()
    {
        return 'Program';
    }
}