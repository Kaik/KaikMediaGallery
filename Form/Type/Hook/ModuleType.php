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

use Kaikmedia\GalleryModule\Form\Type\Hook\AreaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ModuleType extends AbstractHookType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('enabled', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
        ->add('areas', CollectionType::class, [
                    'entry_type' => AreaType::class,
                    'required' => false
        ])
        ;
    }

//    public function getName()
//    {
//        return 'kaikmedia_gallery_module_module_type';
//    }

    public function getBlockPrefix()
    {
        return 'kaikmedia_gallery_module_module_settings_type';
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
            'data_class' => 'Kaikmedia\GalleryModule\Hooks\HookedModuleObject'
        ]);
        $resolver->setNormalizer('options', $optionsNormalizer);
    }
}
