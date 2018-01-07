<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Form\Type\Settings;

use Kaikmedia\GalleryModule\Form\Type\Hook\HooksType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//use Symfony\Component\Form\Extension\Core\Type\IntegerType;
//use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gallery_enabled', ChoiceType::class,
                ['choices' => ['Off' => '0', 'On' => '1'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
            ->add('gallery_disabled_info', TextareaType::class, [
                'required' => false
            ])
            ->add('upload_dir', TextType::class, [
    //            'disabled' => true,
                'required' => true
            ])
            // Hooks
            ->add('hooks', HooksType::class)
            ->add('restore', SubmitType::class, [])
            ->add('save', SubmitType::class, []);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'settingsManager' => false,
        ]);
    }
}