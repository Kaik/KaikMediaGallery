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
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryProviderDisplaySettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('feature', TextType::class, [
                'required' => true
            ])
        ->add('mode', ChoiceType::class, ['choices' => ['Templated' => '0', 'Raw' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])
        ->add('width', TextType::class, [
                'required' => true
            ])
        ->add('height', TextType::class, [
                'required' => true
            ])
        ->add('css_class', TextType::class, [
                'required' => true
            ])
        ->add('autoplay', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])
        ->add('show_title', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])
        ->add('show_description', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
            ])
        ->add('show_legal', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
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
        return "kmgallery_provider_gallery_settings_display";
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'settingsManager' => false,
        ]);
    }
}