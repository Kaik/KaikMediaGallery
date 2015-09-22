<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaRelationDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'required' => false
        ));
        
        $builder->add('value', 'textarea', array(
        		'required' => false
        ));                       
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'isXmlHttpRequest' => false,
            'data_class' => 'Kaikmedia\GalleryModule\Entity\MediaRelationDataEntity'
        ));
    }

    public function getName()
    {
        return 'media_relation_data';
    }
}