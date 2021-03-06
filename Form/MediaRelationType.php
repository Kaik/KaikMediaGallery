<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaRelationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', 'text', ['disabled' => true,
            								'required' => false
        ]);

        $builder->add('details','collection', [
        		'type' => new MediaRelationDataType(),
        		'prototype' => false,
        		'by_reference' => false,
        		'allow_add'          => false,
        		'allow_delete'       => false
        ]);

        if ($options['isXmlHttpRequest'] == false) {
            $builder->add('save', 'submit', ['label' => 'Save']);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'isXmlHttpRequest' => false,
            'data_class' => 'Kaikmedia\GalleryModule\Entity\MediaRelationsEntity'
        ]);
    }

    public function getName()
    {
        return 'media_relation';
    }
}