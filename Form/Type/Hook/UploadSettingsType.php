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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UploadSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, [
    //            'disabled' => true,
                'required' => true
            ])
        ->add('title', TextType::class, [
    //            'disabled' => true,
                'required' => true
            ])
        ->add('prefix', TextType::class, [
    //            'disabled' => true,
                'required' => false
            ])
        ->add('dir', TextType::class, [
    //            'disabled' => true,
                'required' => false
            ])
        ->add('mimeTypes', TextType::class, [
    //            'disabled' => true,
                'required' => true
            ])
        ->add('singleFileMaxSize', TextType::class, [
    //            'disabled' => true,
                'required' => false
            ])
        ->add('plugin_item_css', TextType::class, [
    //            'disabled' => true,
                'required' => true
            ])
        ->add('multiple', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
                'data'      => '0'
            ])
        ->add('extra_title', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
                'data'      => '0'
            ])
        ->add('extra_legal', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
                'data'      => '0'
            ])
        ;
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