<?php

/**
 * Copyright (c) KaikMedia.com 2015
 */

namespace Kaikmedia\GalleryModule\Form\Media;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Kaikmedia\GalleryModule\Form\DataTransformer\JsonToArrayTransformer;

class ImageMediaType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
//    protected $translator;
//    private $type;
//
//    public function __construct($type = 'item') {
//        $this->type = $type;
//    }
//
//    public function setTranslator(TranslatorInterface $translator) {
//        $this->translator = $translator;
//    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder->add('title', TextType::class, [
                    'required' => false
                ])
                ->add('description', TextareaType::class, [
                    'required' => false
                ])
                ->add('legal', TextareaType::class, [
                    'required' => false
                ])
                ->add('mediaExtra', TextareaType::class, [
                    'required' => false
                ])
                ->addModelTransformer(new JsonToArrayTransformer())
                
                ->add('publicdomain', CheckboxType::class, [
                    'required' => false
        ]);

        $builder->add('file', FileType::class);

        if ($options['isXmlHttpRequest'] == false) {
            $builder->add('save', SubmitType::class);
        }
        
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /** @var ClienteTemp $data */
            $eventData = $event->getData();
            $formData = $event->getForm();
            
            $eventData->setMediaExtra(json_decode($eventData->getMediaExtra(), true));
            
            // PRE_SUBMIT
//            $eventData !== $formData
            // (array from request) !== (model pre set data)
            
            // SUBMIT
//            $eventData !== $formData
            // (normalized data after view reverse transform) !== (model pre set data)

            // POST_SUBMIT
//            $eventData === $formData
            // (model data hydrated after submission in both case)
            
        });  
        
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'isXmlHttpRequest' => false,
            'csrf_protection' => false,
            'data_class' => 'Kaikmedia\\GalleryModule\\Entity\\Media\\ImageEntity'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'media_image';
    }
}