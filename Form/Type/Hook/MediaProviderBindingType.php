<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Form\Type\Hook;

use Kaikmedia\GalleryModule\Form\Type\Hook\UploadSettingsType;
//use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * MediaProviderBindingType
 *
 * @author Kaik
 */
class MediaProviderBindingType extends AbstractHookType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//        ->add('topic_mode', ChoiceType::class, [
//            'choices' => [
//                '0' => 'Admin',
//                '1' => 'Owner',
//                '2' => 'First comment',
//            ],
//        ])
//        ->add('delete_action', ChoiceType::class, [
//            'choices' => [
//                'none' => 'Do nothing',
//                'lock' => 'Lock topic',
//                'remove' => 'Delete topic',
//                ],
//        ])
//        ->add('forum', ForumSelectType::class, [])

            ->add('features', CollectionType::class, [
                'entry_type' => UploadSettingsType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'required'     => false,
//                'attr'         => [
//                    'class' => 'my-selector',
//                ],
//                'by_reference' => false,
            ])
        ;
    }

//    public function getName()
//    {
//        return 'media_provider_binding_type';
//    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $optionsNormalizer = function (Options $options, $value) {
            $value['block_name'] = 'entry';

            return $value;
        };
        $resolver->setDefaults([
            'allow_add' => false,
            'allow_delete' => false,
            'prototype' => true,
            'prototype_name' => '__name__',
            'type' => 'text',
            'options' => [],
//            'attr' => [
//                'class' => 'my-selector',
//            ],
            'delete_empty' => false,
        ]);
        $resolver->setNormalizer('options', $optionsNormalizer);
    }
}
