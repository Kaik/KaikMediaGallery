<?php

/**
 * Copyright (c) KaikMedia.com 2015
 */

namespace Kaikmedia\GalleryModule\Form\Features;

use Kaikmedia\GalleryModule\Form\Features\AbstractFeatureType;
use Symfony\Component\Form\FormBuilderInterface;

class UploadFeatureType extends AbstractFeatureType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder->add('uploadDir', 'text');
        $builder->add('uploadMaxFiles', 'text');
        $builder->add('uploadMaxSingleSize', 'text');
        $builder->add('allowedMedia', 'text', [
            'required' => false
            ]);
    }

}
