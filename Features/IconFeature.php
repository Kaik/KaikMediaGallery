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
class IconFeature extends AbstractFeature {
    
    public function __construct() {
        parent::__construct();    
        $this->name = 'icon';
        $this->type = 'feature';
        $this->enabled = true;
        
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