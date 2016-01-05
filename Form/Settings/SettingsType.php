<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Form\Settings;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Kaikmedia\GalleryModule\Form\Settings\ModuleSettingsType;

class SettingsType extends AbstractType
{
    private $data;
    public function __construct($data) {
        $this->data = $data;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        
        $builder->add('modules', 'collection', array(
            'type' => new ModuleSettingsType(),
            'mapped' => false,
            'data' => $this->data
        ));        
        
        if ($options['isXmlHttpRequest'] == false) {
            $builder->add('save', 'submit', array('label' => 'Save'));
        }                       
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'isXmlHttpRequest' => false
        ));
    }
    

    public function getName()
    {
        return 'kaikmedia_gallery_settings';
    }
}