<?php
/**
 * Copyright (c) KaikMedia.com 2014
 */
namespace Kaikmedia\PagesModule\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', [
            'required' => false
        ])
            ->add('path', 'text', [
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
        ])
            ->add('promoted', 'checkbox', [
            'label' => 'promoted',
            'required' => false
        ])
            ->add('file', 'file', [
            'required' => false
        ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'isXmlHttpRequest' => false,
            'data_class' => 'Kaikmedia\PagesModule\Entity\ImageEntity'
        ]);
    }

    public function getName()
    {
        return 'images';
    }
}