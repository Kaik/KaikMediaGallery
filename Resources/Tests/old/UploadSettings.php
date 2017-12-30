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
class UploadSettings extends AbstractFeatureSetting {
    

    public $uploadDir;
    public $uploadMaxFiles;
    public $uploadMaxSingleSize;
    
    public function __construct() {
        $this->name = 'upload';
        $this->enabled = 0; 
        $this->uploadDir = 'userdata';
        $this->uploadMaxFiles = 0;
        $this->uploadMaxSingleSize = 0;      
    } 
    
    public function getUploadDir() {
        return $this->uploadDir;
    }

    public function setUploadDir($uploadDir) {
        $this->uploadDir = $uploadDir;
        return $this;
    }

    public function getUploadMaxFiles() {
        return $this->uploadMaxFiles;
    }

    public function setUploadMaxFiles($uploadMaxFiles) {
        $this->uploadMaxFiles = $uploadMaxFiles;
        return $this;
    }

    public function getUploadMaxSingleSize() {
        return $this->uploadMaxSingleSize;
    }

    public function setUploadMaxSingleSize($uploadMaxSingleSize) {
        $this->uploadMaxSingleSize = $uploadMaxSingleSize;
        return $this;
    }
           
    public function getFormClass() {
      return '\Kaikmedia\\GalleryModule\\Form\\Settings\\UploadSettingsType';  
    }
        
    
    public function toArray() {    
        $array = parent::toArray();
        return $array;
    }
    
}
