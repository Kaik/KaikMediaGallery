<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Media\Handlers;

/**
 *
 * @author Kaik
 */
class AbstractMediaHandler {
//put your code here
    
    public $name;
    
    public $type;
    
    public $enabled;
    
    public function __construct() {
    }   
    
    public function getName(){
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
        return $this;
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
    
    public function getSupportedMimeTypes() {
        return [];
    }  
    
    public function getFormClass() {
      return '\Kaikmedia\\GalleryModule\\Form\\Media\\Handlers' . ucfirst($this->getAlias()) . 'Handler';  //??
    }  
    
    public function getClass() {
        
    } 

    public function getAlias()
    {
        $class = get_class($this);
        $class = explode('\\', $class);
        $class = $class[count($class) - 1];
        return lcfirst($class);
    }      
}
