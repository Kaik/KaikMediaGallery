<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Form\Type;

use ServiceUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Kaikmedia\GalleryModule\Form\DataTransformer\UserToIdTransformer;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        // this assumes that the entity manager was passed in as an optio
        $em = ServiceUtil::getService('doctrine.entitymanager');
        // $entityManager = $options['em'];
        $transformer = new UserToIdTransformer($em);        
        
        $builder->add('title', 'text', array(
            'required' => false
        ))
            ->add($builder->create('author', 'text', ['attr' => ['class' => 'author_search'],
            'required' => false])
            ->addModelTransformer($transformer))
            ->add('parent')
            ->add('description', 'textarea', array(
            'required' => false
        ));
            
       // if ($options['isXmlHttpRequest'] == false) {
            $builder->add('save', 'submit', array('label' => 'Save'));
      //  }                       
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'isXmlHttpRequest' => false,
            'data_class' => 'Kaikmedia\GalleryModule\Entity\AlbumEntity'
        ));
    }

    public function getName()
    {
        return 'album';
    }
}