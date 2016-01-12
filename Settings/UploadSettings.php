<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Settings;

/**
 * Description of UploadSettings
 *
 * @author Kaik
 */
class UploadSettings {
    //put your code here
    public $name;
    public $uploadDir;
    public $uploadMaxFiles;
    public $uploadMaxSingleSize;
    
    public function __construct() {
        $this->name = 'upload';
        $this->uploadDir = 'userdata';
        $this->uploadMaxFiles = 0;
        $this->uploadMaxSingleSize = 0;      
    } 
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
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

    public function getUploadMaxSingleFiles() {
        return $this->uploadMaxSingleSize;
    }

    public function setUploadMaxSingleSize($uploadMaxSingleSize) {
        $this->uploadMaxSingleSize = $uploadMaxSingleSize;
        return $this;
    }
    
    public function getFormClass() {
      return '\Kaikmedia\\GalleryModule\\Form\\Settings\\UploadSettingsType';  
    }    
}
