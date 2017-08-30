<?php
/**
 * Copyright (c) KaikMedia.com 2014
 */
namespace Kaikmedia\PagesModule\Form\Type;

use ServiceUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Kaikmedia\PagesModule\Form\DataTransformer\UserToIdTransformer;
use Kaikmedia\PagesModule\Form\Type\ImageType;

class PageType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // this assumes that the entity manager was passed in as an optio
        $em = ServiceUtil::getService('doctrine.entitymanager');
        // $entityManager = $options['em'];
        $transformer = new UserToIdTransformer($em);
        $builder->setMethod('POST')
            ->add('online', 'choice', [
            'choices' => [
                '0' => 'Offline',
                '1' => 'Online'
            ],
            'multiple' => false,
            'expanded' => true,
            'required' => true
        ])
            ->add('depot', 'choice', [
            'choices' => [
                '0' => 'Depot',
                '1' => 'Allowed'
            ],
            'multiple' => false,
            'expanded' => true,
            'required' => true
        ])
            ->add('inmenu', 'choice', [
            'choices' => [
                '0' => 'Not in menus',
                '1' => 'In menus'
            ],
            'multiple' => false,
            'expanded' => true,
            'required' => true
        ])
            ->add('inlist', 'choice', [
            'choices' => [
                '0' => 'Not in list',
                '1' => 'In List'
            ],
            'multiple' => false,
            'expanded' => true,
            'required' => true
        ])
            ->add('title', 'text', [
            'required' => false
        ])
            ->add('urltitle', 'text', [
            'required' => false
        ])
            ->add($builder->create('author', 'text', [
            'attr' => [
                'class' => 'author_search'
            ]
        ])
            ->addModelTransformer($transformer))
            ->add('views', 'text', [
            'required' => false
        ])
            ->add('publishedAt', 'datetime', [
            'format' => \IntlDateFormatter::SHORT,
            'input' => 'datetime',
            'required' => false,
            'widget' => 'single_text'
        ])
            ->add('expiredAt', 'datetime', [
            'format' => \IntlDateFormatter::SHORT,
            'input' => 'datetime',
            'required' => false,
            'widget' => 'single_text'
        ])
            ->add('images', 'collection', [
            'type' => new ImageType(),
            'allow_add' => true,
            'required' => false,
            'delete_empty' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'prototype' => true
        ])
            ->add('layout', 'choice', [
            'choices' => [
                'default' => 'Default',
                'slider' => 'Slider'
            ],
            'preferred_choices' => [
                'default'
            ],
            'required' => false
        ])
            ->add('language', 'choice', [
            'choices' => [
                'any' => 'Any',
                'en' => 'English',
                'pl' => 'Polish'
            ]
        ])
            ->add('content', 'textarea', [
            'required' => false,
            'attr' => [
                'class' => 'tinymce'
            ]
        ])
            -> // Skip it if you want to use default theme))
add('description', 'textarea', [
            'required' => false,
            'attr' => [
                'class' => 'tinymc'
            ]
        ])
            -> // Skip it if you want to use default theme))
add('save', 'submit', [
            'label' => 'Save'
        ]);
    }

    public function getName()
    {
        return 'pageform';
    }

    /**
     * OptionsResolverInterface is @deprecated and is supposed to be replaced by
     * OptionsResolver but docs not clear on implementation
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'title' => null,
            'content' => null
        ]);
    }
}