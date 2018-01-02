<?php

/**
 * Copyright (c) KaikMedia.com 2014
 */

namespace Kaikmedia\GalleryModule\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class JsonToArrayTransformer implements DataTransformerInterface {

    /**
     * @return string
     */
    public function transform($array) {

        dump($array);
        if (!is_array($array)) {
            return '';
        }
        //return json
        return json_encode($array);
//        return '';
    }

    /**
     * @return User|null
     * @throws TransformationFailedException if object (user) is not found.
     */
    public function reverseTransform($string) {

          dump($string);
//        $modelData = json_decode($string, true);
//        if ($modelData == null) {
//            return [];
//        }
//        // return array
//        return $modelData;
        return [];
    }
}
