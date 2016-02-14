<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Settings;

use Zikula\Core\Doctrine\EntityAccess;
/**
 * Description of AbstractSettingsType
 *
 * @author Kaik
 */
class AbstractFeatureSetting extends EntityAccess {
    
    public $name;
    public $enabled;
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }       

    public function getEnabled() {
        return $this->enabled;
    }

    public function setEnabled($enabled) {
        $this->enabled = $enabled;
        return $this;
    }    
    
    public function toArray() {
        
        $array = parent::toArray();
        $array['name'] = $this->getName();
        return $array;
    }    
    
}
