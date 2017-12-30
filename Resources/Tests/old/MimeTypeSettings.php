<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Settings;

use Kaikmedia\GalleryModule\Settings\AbstractFeatureSetting;

/**
 * Description of UploadSettings
 *
 * @author Kaik
 */
class MimeTypeSettings extends AbstractFeatureSetting {
    //put your code here
    
    public $handler;
    public $mimeType;
    
    public function __construct() {
        $this->name = 'mimetype';
        $this->enabled = 0;      
    } 

    public function getHandler() {
        return $this->handler;
    }

    public function setHandler($handler) {
        $this->handler = $handler;
        return $this;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return Image
     */
    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType() {
        return $this->mimeType;
    }    
       
    public function getFormClass() {
      return '\Kaikmedia\\GalleryModule\\Form\\Settings\\MimeTypeSettingsType';  
    }
 
}
