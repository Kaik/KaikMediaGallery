<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Form\Features;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class AbstractFeatureType extends AbstractType
{  
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder->add('enabled','choice',[
            'choices' => [
                '0' => 'Off',
                '1' => 'On'
            ],
            'multiple' => false,
            'expanded' => true,
            'required' => true
        ]);
        $builder->add('name','text',['disabled' => true]);
        $builder->add('type','text',['disabled' => true]);
    }  
    
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $class = get_class($this);
        $class = substr($class, strlen('Kaikmedia\\GalleryModule\\Form\\Features\\'));
        $class = substr($class, 0, -strlen('Type'));
        $resolver->setDefaults([
            'data_class' => 'Kaikmedia\\GalleryModule\\Features\\' . $class
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $class = get_class($this);
        $class = substr($class, strlen('Kaikmedia\\GalleryModule\\Form\\Features\\'));
        $class = str_replace('\\', '_', $class);
        return 'kaikmedia_gallery_' . strtolower($class);
    }    
    
}