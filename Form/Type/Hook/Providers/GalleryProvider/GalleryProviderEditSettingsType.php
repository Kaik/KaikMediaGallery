<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Form\Type\Hook\Providers\GalleryProvider;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Kaikmedia\GalleryModule\Form\Type\TranslatableType;
use Kaikmedia\GalleryModule\Form\Type\Settings\MimeTypeSelectType;

class GalleryProviderEditSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, [
                'required' => true
            ])
        ->add('title', TranslatableType::class, [
                'required' => true
            ])
        ->add('prefix', TextType::class, [
                'required' => false
            ])
        ->add('dir', TextType::class, [
                'required' => false
            ])
        ->add('mimeTypes', MimeTypeSelectType::class, [])   
        ->add('multiple', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])
        ->add('maxitems', TextType::class, [
                'required' => false
            ])
        ->add('maxsize', TextType::class, [
                'required' => false
            ])
        ->add('plugin_item_css', TextType::class, [
                'required' => true
            ])
        ->add('preview_css_class', TextType::class, [
                'required' => true
            ])
        ->add('enable_info', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])
        ->add('enable_styles', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])
        ->add('styles_allowed', ChoiceType::class, ['choices' => 
                ['Max'       => 'max', 
                'Slider'    => 'slider',
                'Tiles'     => 'tiles',
                'List'      => 'list',
                ],
                'multiple'  => true,
                'expanded'  => true,
                'required'  => true,
        ])
        ->add('styles_default', ChoiceType::class, ['choices' => 
                ['Max'       => 'max', 
                'Slider'    => 'slider',
                'Tiles'     => 'tiles',
                'List'      => 'list',
                ],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
        ])                
        ->add('enable_editor', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])
        ->add('editor_positioning', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])
        ->add('editor_rotate', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])
        ->add('enable_extra', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])        
        ->add('extra_title', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])
        ->add('extra_description', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])
        ->add('extra_legal', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])
        ;
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix()
    {
        return "kmgallery_provider_gallery_settings_edit";
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
            'matchedEvents' => [],
            'prototype_name' => '__name__',
            'type' => 'text',
            'options' => [],
            'delete_empty' => false,
        ]);
        $resolver->setNormalizer('options', $optionsNormalizer);
    }
}