<?php

/**
 * Copyright (c) KaikMedia.com 2014
 */

namespace Kaikmedia\GalleryModule\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class JsonToArrayTransformer implements DataTransformerInterface {

    /**
     *
     * @param ObjectManager $om            
     */
    public function __construct() {
        
    }

    /**          
     * @return string
     */
    public function transform($array) {
        if (!is_array($array)) {
            return [];
        }
        //return json
        return json_encode($array);
    }

    /**          
     * @return User|null
     * @throws TransformationFailedException if object (user) is not found.
     */
    public function reverseTransform($string) {
        $modelData = json_decode($string, true);
        if ($modelData == null) {
            throw new TransformationFailedException('String is not a valid JSON.');
        }
        // return array
        return $modelData;
    }

}
