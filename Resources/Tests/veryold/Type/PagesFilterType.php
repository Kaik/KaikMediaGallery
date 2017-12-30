<?php
/**
 * Copyright (c) KaikMedia.com 2014
 */
namespace Kaikmedia\PagesModule\Form\Type;

use ServiceUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PagesFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('GET')
            ->add('limit', 'choice', [
            'choices' => [
                '10' => '10',
                '25' => '25',
                '50' => '50'
            ],
            'required' => false,
            'data' => $options['limit']
        ])
            ->add('title', 'text', [
            'required' => false,
            'data' => $options['title']
        ])
            ->add('online', 'choice', [
            'choices' => [
                'online' => 'Online',
                'offline' => 'Offline'
            ],
            'required' => false,
            'data' => $options['online']
        ])
            ->add('filter', 'submit', [
            'label' => 'Filter'
        ]);
    }

    public function getName()
    {
        return 'pagesfilterform';
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
            'limit' => null,
            'title' => null,
            'online' => null
        ]);
    }
}