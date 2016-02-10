<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Settings;

use Kaikmedia\GalleryModule\Features\FeaturesCollection;
use Zikula\Core\Doctrine\EntityAccess;

/**
 * Description of SettingsObject
 *
 * @author Kaik
 */
class SettingsObject extends EntityAccess {

    //put your code here

    public $name;
    public $displayName;
    public $enabled;
    public $features;
    public $entity;
    public $extra;
    public $default;
    
    
    public function __construct() {
        
        $this->name = 'KaikmediaGalleryModule';
        $this->displayName = 'KMGallery';
        $this->enabled = true;
        $this->default = $this->IsDefault();
        $this->entity = false;
        $this->extra = false;
        $this->features = new FeaturesCollection();
    }
    
    public function IsDefault() {
        return $this->name === 'KaikmediaGalleryModule' ? true : false;
    }    

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getDisplayName() {
        return $this->displayName;
    }

    public function setDisplayName($displayName) {
        $this->displayName = $displayName;
    }    

    public function getEnabled() {
        return $this->enabled;
    }

    public function setEnabled($enabled) {
        $this->enabled = $enabled;
        return $this;
    }    
      
    public function getFeatures() {
        return $this->features;
    }

    public function setFeatures($features) {
       
        $this->features = $features;
    }    

    public function getEntity() {
        return $this->entity;
    }

    public function setEntity($entity) {
        $this->entity = $entity;
    }
    
    public function getExtra() {
        return $this->extra;
    }

    public function setExtra($extra) {
        $this->extra = $extra;
    }
    
    public function mergeSettings($settings) {
        
    }
    
    public function __toString() {
       return $this->name;
    }
    
    public function toArray() {
        
        $array = parent::toArray();
        /*
        $array = ['name' => $this->getName(),
                  'displayName' => $this->getDisplayName(),
                  'features' => $this->features->toArray(),
                  'enabled' => $this->getEnabled(),            
        ];
        */
        return $array;
    }
}
