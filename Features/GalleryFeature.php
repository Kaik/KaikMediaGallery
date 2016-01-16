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
class GalleryFeature extends AbstractFeature {
    
    public $perpage;
    
    public function __construct() {
        parent::__construct();    
        $this->name = 'gallery';
        $this->type = 'insert';
        $this->enabled = 0;
        $this->icon = 'fa fa-th-large';    
        $this->perpage = 25;
    }
    
    public function getDisplayName() {
        return 'Insert gallery';
    }    
    
    public function getPerpage() {
        return $this->perpage;
    }

    public function setPerpage($perpage) {
        $this->perpage = $perpage;
        return $this;
    }   
}
