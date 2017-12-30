<?php

/**
 * Copyright (c) KaikMedia.com 2015
 */

namespace Kaikmedia\GalleryModule\Form\Features;

use Kaikmedia\GalleryModule\Form\Features\AbstractFeatureType;
use Symfony\Component\Form\FormBuilderInterface;

class AddmediaFeatureType extends AbstractFeatureType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder->add('settings', 'addmediasettings');
    }

}
