<?php

namespace Eifl\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LessonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lesson','text',array(
            'label'=>'Lesson Name',
            'required'=>true,
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eifl\AdminBundle\Entity\Lesson',
        ));
    }

    public function getName()
    {
        return 'level';
    }
}