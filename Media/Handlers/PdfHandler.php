<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Media\Handlers;

/**
 * Description of PdfHandler
 *
 * @author Kaik
 */
class PdfHandler extends AbstractMediaHandler {

    //put your code here

    public function __construct() {
        parent::__construct();
        
    }

    public function getSupportedMimeTypes() {
    return [
            'application/pdf' => ['handler' => 'pdf', 'icon' => 'fa fa-pdf-o'],
            'application/x-pdf' => ['handler' => 'pdf', 'icon' => 'fa fa-pdf-o'],
        ];
    }
}