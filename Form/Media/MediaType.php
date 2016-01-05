<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Form\Media;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'required' => false,
            'mapped'   => false
        ))
            ->add('path', 'text', array(
            'required' => false,
            'mapped'   => false
        ))
            ->add('description', 'textarea', array(
            'required' => false
        ))
            ->add('legal', 'textarea', array(
            'required' => false
        ))
            ->add('publicdomain', 'checkbox', array(
            'label' => 'public',
            'required' => false
        ))
            ->add('file', 'file', array(
            'required' => false,
            'mapped'   => false
        ));
            
        if ($options['isXmlHttpRequest'] == false) {
            $builder->add('save', 'submit', array('label' => 'Save'));
        }                       
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'isXmlHttpRequest' => false,
            'data_class' => 'Kaikmedia\GalleryModule\Entity\Media\ImageEntity'
        ));
    }

    public function getName()
    {
        return 'media';
    }
}