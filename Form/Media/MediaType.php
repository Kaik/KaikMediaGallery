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
        $builder->add('title', 'text', [
            'required' => false
        ])
            ->add('description', 'textarea', [
            'required' => false
        ])
            ->add('legal', 'textarea', [
            'required' => false
        ])
            ->add('publicdomain', 'checkbox', [
            'label' => 'public',
            'required' => false
        ]);
            
        if ($options['isXmlHttpRequest'] == false) {
            $builder->add('save', 'submit', ['label' => 'Save']);
        }                       
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'isXmlHttpRequest' => false,
            'data_class' => 'Kaikmedia\GalleryModule\Entity\Media\ImageEntity'
        ]);
    }

    public function getName()
    {
        return 'media';
    }
}