<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Media\Handlers;

/**
 * Description of ImageHandler
 *
 * @author Kaik
 */
class ImageHandler extends AbstractMediaHandler {

    //put your code here

    public function __construct() {
        parent::__construct();
    }

    public function getSupportedMimeTypes() {
        return [
            'image/gif' => ['handler' => 'image', 'icon' => 'fa fa-image-o', 'name' => 'image:gif'],
            'image/jpeg' => ['handler' => 'image', 'icon' => 'fa fa-image-o', 'name' => 'image:jpeg'],
            'image/png' => ['handler' => 'image', 'icon' => 'fa fa-image-o', 'name' => 'image:png'],
        ];
    }

}
