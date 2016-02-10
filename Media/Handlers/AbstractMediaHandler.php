<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Media\Handlers;

use Zikula\Core\Doctrine\EntityAccess;
/**
 *
 * @author Kaik
 */
class AbstractMediaHandler extends EntityAccess {
//put your code here
    
    public $name;
    
    public $type;
    
    public $enabled;
    
    public function __construct() {
        $this->setName($this->getAlias());
        $this->setType(substr($this->name,0,-7));
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
      return '\Kaikmedia\\GalleryModule\\Form\\Media\\MediaType';  //default
    }  
 
    public function getEntityClass() {
      return '\Kaikmedia\\GalleryModule\\Entity\\Media\\' . ucfirst($this->type) . 'Entity';  //mediaitem class
    }    

    public function getClass() {
      return '\Kaikmedia\\GalleryModule\\Entity\\Media\\' . ucfirst($this->type) . 'Entity';  //mediaitem class
    } 

    public function getAlias()
    {
        $class = get_class($this);
        $class = explode('\\', $class);
        $class = $class[count($class) - 1];
        return lcfirst($class);
    }     
      
}
