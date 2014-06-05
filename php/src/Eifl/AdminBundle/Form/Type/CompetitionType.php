<?php

namespace Eifl\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompetitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',array(
                'label'=>'Competition Name'
            ))
            ->add('type','choice',array(
                'label'=>'Type',
                'choices'=>array(
                    'TO'=>'Try Out',
                    'PC'=>'Public Competition'
                )
            ))
            ->add('date','date',array(
                'label'=>'Date'
            ))
            ->add('fee','text',array(
                'label'=>'Registration Fee'
            ))
            ->add('stime','time',array(
                'label'=>'Start Time'
            ))
            ->add('etime','time',array(
                'label'=>'End Time'
            ))
            ->add('location','text',array(
                'label'=>'location'
            ))
            ->add('firstwinner','text',array(
                'label'=>'1st Prize'
            ))
            ->add('secondwinner','text',array(
                'label'=>'2nd Prize'
            ))
            ->add('thirdwinner','text',array(
                'label'=>'3th Prize'
            ))
            ->add('save','submit',array(
                'label'=>'Save'
            ));
    }

    public function getName()
    {
        return 'Competition';
    }
}