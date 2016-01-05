<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Form\Settings;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModuleSettingsType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder->add('enabled','choice', array(
            'choices' => array(
                '0' => 'Off',
                '1' => 'On'
            ),
            'multiple' => false,
            'expanded' => true,
            'required' => true
        ));            

        $builder->add('features', 'features');                
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'isXmlHttpRequest' => false         
        ));
    }

    public function getName()
    {
        return 'kaikmedia_gallery_module_settings';
    }
}