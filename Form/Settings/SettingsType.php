<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Form\Settings;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Kaikmedia\GalleryModule\Form\Settings\ObjectSettingsType;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('settings', 'collection', [
            'type' => new ObjectSettingsType()
            ]
                );

        if ($options['isXmlHttpRequest'] == false) {
            $builder->add('save', 'submit', ['label' => 'Save']);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'isXmlHttpRequest' => false
        ]);
    }


    public function getName()
    {
        return 'kaikmedia_gallery_settings';
    }
}