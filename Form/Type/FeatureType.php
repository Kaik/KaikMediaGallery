<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeatureType extends AbstractType
{  
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder->add('enabled','choice', array(
            'choices' => array(
                '0' => 'Offline',
                '1' => 'Online'
            ),
            'multiple' => false,
            'expanded' => true,
            'required' => true
        )); 
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'isXmlHttpRequest' => false
        ));
    }

    public function getName()
    {
        return 'kaikmedia_gallery_feature_settings';
    }
}