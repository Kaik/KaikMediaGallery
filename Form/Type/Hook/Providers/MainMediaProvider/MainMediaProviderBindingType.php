<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Form\Type\Hook\Providers\MainMediaProvider;

//use Kaikmedia\GalleryModule\Form\Type\Hook\MainMediaProviderEditSettingsType;
//use Kaikmedia\GalleryModule\Form\Type\Hook\MainMediaProviderDisplaySettingsType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Kaikmedia\GalleryModule\Form\Type\Hook\AbstractHookType;

/**
 * MainMediaProviderBindingType
 *
 * @author Kaik
 */
class MainMediaProviderBindingType extends AbstractHookType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
//    "display_view" => "view"
//    "form_edit" => "edit"
//    "validate_edit" => "validateEdit"
//    "process_edit" => "processEdit"
//    "form_delete" => "delete"
//    "validate_delete" => "validateDelete"
//    "process_delete" => "processDelete"
        
        if (is_array($options) && array_key_exists('matchedEvents', $options)) {
            if (array_key_exists('display_view', $options['matchedEvents'])) {
                $builder->add('display', MainMediaProviderDisplaySettingsType::class, [
                    'required'     => false,
                ]);
            }
       
            if (array_key_exists('form_edit', $options['matchedEvents'])) {
                $builder->add('edit', MainMediaProviderEditSettingsType::class, [
                    'required'     => false,
                ]);
            } 
        
         }
         
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
        return "kmgallery_provider_mainmedia_binding";
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
