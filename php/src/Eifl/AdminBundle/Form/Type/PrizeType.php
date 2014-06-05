<?php

namespace Eifl\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PrizeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image','file',array(
                'label'=>'Image'
            ))
            ->add('name','text',array(
                'label'=>'Name'
            ))
            ->add('point','text',array(
                'label'=>'Point/Unit'
            ))
            ->add('unit','text',array(
                'label'=>'Unit'
            ))
            ->add('save','submit',array(
                'label'=>'Save'
            ));
            
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eifl\AdminBundle\Entity\Prize',
        ));
    }

    public function getName()
    {
        return 'Prize';
    }
}