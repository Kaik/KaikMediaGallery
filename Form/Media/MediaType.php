<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Form\Media;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

class MediaType extends AbstractType
{
    
   /**
     * @var TranslatorInterface
     */
    protected $translator;

    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }    
    
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    } 

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

       $builder->add('title', 'text', [
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
        ]);
           
        $builder->add('file');       
       
        if ($options['isXmlHttpRequest'] == false) {
            $builder->add('save', 'submit', ['label' => 'Save']);
        }                       
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'isXmlHttpRequest' => false,
            'data_class' => 'Kaikmedia\\GalleryModule\\Entity\\Media\\' . lcfirst($this->type).'Entity'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'media_' . strtolower($this->type);
    }    

}
