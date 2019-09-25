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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Kaikmedia\GalleryModule\Form\Type\Hook\Providers\HooksProvidersType;
use Kaikmedia\GalleryModule\Form\Type\Hook\HooksSubscribersType;

class HooksType extends AbstractType
{
    public function __construct()
    {
        $this->name = 'KaikmediaGalleryModule';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                // Hooks P
                ->add('providers', HooksProvidersType::class)
                // Hooks S
                ->add('subscribers', HooksSubscribersType::class)
        ;
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
