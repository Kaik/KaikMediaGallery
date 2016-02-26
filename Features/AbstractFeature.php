<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Features;

use Kaikmedia\GalleryModule\Features\FeatureSettingsCollection;

/**
 * Description of AbstractFeature
 *
 * @author Kaik
 */
class AbstractFeature {
    //put your code here
    
    public $name;
    
    public $type;
    
    public $enabled;
    
    public $icon;
    
    public $settings;
    
    public function __construct() {
       $this->settings = new FeatureSettingsCollection();
    }   
    
    public function getName(){
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    public function getDisplayName() {
        return 'Feature';
    }   
    
    public function getType() {
        return $this->type;
    }
    
    public function setType($type) {
        $this->type = $type;
        return $this;
    }
    
    public function getEnabled() {
        return $this->enabled;
    }
    
    public function setEnabled($enabled) {
        $this->enabled = $enabled;
        return $this;
    }
    
    public function getIcon(){
        return $this->icon;
    }
    
    public function setIcon($icon) {
        $this->icon = $icon;
        return $this;
    }   
    
    public function getSettings() {
        return $this->settings;
    }

    public function setSettings($settings) {
        $this->settings = $settings;
        return $this;
    }    
    
    public function getFormClass() {
      return '\Kaikmedia\\GalleryModule\\Form\\Features\\' . ucfirst($this->getAlias()) . 'Type';  
    }  
    
    public function getClass() {
        
    }   
    
    public function mergeSettings($settings) {
        $enabled = ($settings->getEnabled() == 1) ? $this->getEnabled() : 0;
        $this->setEnabled($enabled);
        
        return $this;
    }

    public function getAlias()
    {
        $class = get_class($this);
        $class = explode('\\', $class);
        $class = $class[count($class) - 1];
        return lcfirst($class);
    }    
    
    public function toArray() {
        
        $array = ['name' => $this->getName(),
                  'icon' => $this->getIcon(),
                  'displayName' => $this->getDisplayName(),
                  'enabled' => $this->getEnabled(), 
                  'type' => $this->getType(),
                  'formClass' => $this->getFormClass()
        ];
        
        $array['settings'] = ($this->settings instanceof FeatureSettingsCollection ) ? $this->settings->toArray() : null ;
        
        return $array;
    }
   
}
