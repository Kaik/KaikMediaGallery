<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Form\Settings;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MimeTypeSettingsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
          $builder->add('name', 'hidden');
          $builder->add('mimeType', 'text');         
          $builder->add('handler', 'text');
          $builder->add('enabled', 'text');
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
