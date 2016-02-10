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
        $this->add($uploadSettings);
        
        $mediaHandlersManager = new MediaHandlersManager();
        //now we have all features with default settings as array collection
        $mimeTypes = $mediaHandlersManager->getSupportedMimeTypes();
        foreach ($mimeTypes as $mimeType => $data) {
            $mimeTypeSettings = new MimeTypeSettings();
            $mimeTypeSettings->setMimeType($mimeType);
            $mimeTypeSettings->setHandler($data['handler']);
            $this->add($mimeTypeSettings);
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
            if ($element instanceof \Kaikmedia\GalleryModule\Form\Settings\AbstractSettingsType) {
                $array[$key] = $element->toArray();
            }
        }
        return $array;
    }    
}
