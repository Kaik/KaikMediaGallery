<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Features;

use Kaikmedia\GalleryModule\Features\AbstractFeature;

/**
 * Description of IconFeature
 *
 * @author Kaik
 */
class AddmediaFeature extends AbstractFeature {

    public $uploadDir;
    public $uploadMaxFiles;
    public $uploadMaxSingleSize;
    public $allowedMedia;

    public function __construct() {
        parent::__construct();
        $this->name = 'addmedia';
        $this->type = 'origin';
        $this->enabled = 0;
        $this->icon = 'fa fa-plus';
        $this->uploadDir = 'userdata';
        $this->uploadMaxFiles = 0;
        $this->uploadMaxSingleSize = 0;
        $this->allowedMedia = '';
    }

    public function getDisplayName() {
        return 'Add media';
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

    public function getAllowedMedia() {
        return $this->allowedMedia;
    }

    public function setAllowedMedia($allowedMedia) {
        $this->allowedMedia = $allowedMedia;
        return $this;
    }

    public function getDefaultSettings() {

        return [//display
            'width' => '100', // icon width
            'height' => '100', // icon height single preview
            'perpage' => '', //
            //upload
            'extensions' => 'png,jpg', //
            'mimetypes' => 'image', //
            //select
            'maxitems' => '', //
            'type' => 'feature',
            'fields' => 'name,description,alt']; //        
    }

}
