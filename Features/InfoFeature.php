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
class InfoFeature extends AbstractFeature {
    
    
    public function __construct() {
        parent::__construct();    
        $this->name = 'info';
        $this->type = 'info';
        $this->enabled = 0;
        $this->icon = 'fa fa-info-circle';       
    }  

    public function getDisplayName() {
        return 'Info';
    } 
}
