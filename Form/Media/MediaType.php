<?php

/**
 * Copyright (c) KaikMedia.com 2015
 */

namespace Kaikmedia\GalleryModule\Form\Media;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
//use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MediaType extends AbstractType
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
//                ->add('mediaExtra', TextareaType::class, [
//                    'required' => false
//                ])//->addModelTransformer(new JsonToArrayTransformer())
                ->add('publicdomain', CheckboxType::class, [
                    'required' => false
        ]);

//        $builder->add('file', FileType::class);

//        if ($options['isXmlHttpRequest'] == false) {
//            $builder->add('save', SubmitType::class);
//        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'isXmlHttpRequest' => false,
            'csrf_protection' => false,
//            'data_class' => 'Kaikmedia\\GalleryModule\\Entity\\Media\\' . ucfirst($this->type) . 'Entity'
        ]);
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function getName() {
////        return 'media_' . strtolower($this->type);
//    }

}
//'media'; //