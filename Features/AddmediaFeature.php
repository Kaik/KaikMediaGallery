<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Features;

use Kaikmedia\GalleryModule\Features\AbstractFeature;
use Doctrine\Common\Collections\ArrayCollection;
use Kaikmedia\GalleryModule\Media\MediaHandlersManager;
use Kaikmedia\GalleryModule\Settings\UploadSettings;
use Kaikmedia\GalleryModule\Settings\MimeTypeSettings;
/**
 * Description of IconFeature
 *
 * @author Kaik
 */
class AddmediaFeature extends AbstractFeature {
   
    /*

    */
   public $settings;


   public function __construct() {
        parent::__construct();
        $this->name = 'addmedia';
        $this->type = 'origin';
        $this->enabled = 0;
        $this->icon = 'fa fa-plus';
        $this->settings = new ArrayCollection();
        $this->setDefaultSettings();
    }

    public function getDisplayName() {
        return 'Add media';
    }   
    
    public function getSettings() {
        return $this->settings;
    } 

    public function setSettings($settings) {
        $this->settings = $settings;
        return $this;
    }     
    
    public function setDefaultSettings() {
        
        $uploadSettings = new UploadSettings();
        $this->settings->add($uploadSettings);
        $mediaHandlersManager = new MediaHandlersManager();
        $mimeTypes = $mediaHandlersManager->getSupportedMimeTypes();
        foreach($mimeTypes as $mimeType => $handler){           
            $mimeTypeSettings = new MimeTypeSettings();
            $mimeTypeSettings->setMimeType($mimeType);
            $mimeTypeSettings->setHandler($handler);
            $this->settings->add($mimeTypeSettings);              
        }

  
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
