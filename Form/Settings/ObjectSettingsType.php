<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Form\Settings;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Kaikmedia\GalleryModule\Form\Type\FeaturesType;

class ObjectSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('enabled','choice', [
            'choices' => [
                '0' => 'Off',
                '1' => 'On'
            ],
            'multiple' => false,
            'expanded' => true,
            'required' => true
        ]);

        $builder->add('features', 'features',[
            'type' => new FeaturesType()
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'isXmlHttpRequest' => false,
            'data_class' => 'Kaikmedia\GalleryModule\Settings\SettingsObject'
        ]);
    }

    public function getName()
    {
        return 'kaikmedia_gallery_object_settings';
    }
}