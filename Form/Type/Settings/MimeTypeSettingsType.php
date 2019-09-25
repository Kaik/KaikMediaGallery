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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MimeTypeSettingsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('name', HiddenType::class);
        
        $builder->add('mimeType', HiddenType::class);
        
        $builder->add('handler', HiddenType::class);
        
        $builder->add('enabled', ChoiceType::class, ['choices' => ['Off' => '0', 'On' => '1'],
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults([
            'isXmlHttpRequest' => false,
            'data_class' => 'Kaikmedia\GalleryModule\Settings\MimeTypeSettings'
        ]);
    }

    public function getName() {
        return 'kaikmedia_gallery_module_mimetype_settings';
    }

}
