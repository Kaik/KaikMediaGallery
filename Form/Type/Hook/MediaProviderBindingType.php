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

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
//use Zikula\DizkusModule\Form\Type\Forum\ForumSelectType;

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

        ;
    }

    public function getName()
    {
        return 'media_provider_binding_type';
    }

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
            'delete_empty' => false,
        ]);
        $resolver->setNormalizer('options', $optionsNormalizer);
    }
}
