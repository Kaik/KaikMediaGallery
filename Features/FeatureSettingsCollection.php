<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Features;

use Doctrine\Common\Collections\ArrayCollection;
use Kaikmedia\GalleryModule\Settings\UploadSettings;
use Kaikmedia\GalleryModule\Settings\MimeTypeSettings;
use Kaikmedia\GalleryModule\Media\MediaHandlersManager;
/**
 * Description of FeatureSettingsCollection
 *
 * @author Kaik
 */
class FeatureSettingsCollection extends ArrayCollection {
    
    
    /**
     * Initializes a new ArrayCollection.
     *
     * @param array $elements
     */
    public function __construct()
    {   
        $elements = [];
        parent::__construct($elements);   
        
        //Add upload settings
        $uploadSettings = new UploadSettings();
        $this->set($uploadSettings->getName(), $uploadSettings);
        
        $mediaHandlersManager = new MediaHandlersManager();
        //now we have all features with default settings as array collection
        $mimeTypes = $mediaHandlersManager->getSupportedMimeTypes();
        foreach ($mimeTypes as $mimeType => $data) {
            $mimeTypeSettings = new MimeTypeSettings();
            $mimeTypeSettings->setName($data['name']);
            $mimeTypeSettings->setMimeType($mimeType);
            $mimeTypeSettings->setHandler($data['handler']);
            $this->set($mimeTypeSettings->getName(), $mimeTypeSettings);
        }
 
    }    

   public function postSubmit($new, $global) {

        $elements = parent::toArray();
       
        foreach($elements as $key => $element ) {

            $globalSettingData = $global->get($key);
            
            if($new === null){    
                $element->setEnabled(0);
                continue;
            }
            
            $newSettingData = $new->get($key);
            
            $enabled = $globalSettingData->getEnabled() === 1 ? $newSettingData->getEnabled() : 0;  
            $element->setEnabled($enabled);       
            
        } 
    }        
    
    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $elements = parent::toArray();
        $array = [];
        foreach ($elements as $key => $element) {
            if ($element instanceof \Kaikmedia\GalleryModule\Settings\AbstractFeatureSetting) {
                $array[$key] = $element->toArray();
            }
        }
        return $array;
    }    
}
