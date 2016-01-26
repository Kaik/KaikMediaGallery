<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Media;

use Kaikmedia\GalleryModule\Media\MediaHandlersManager;

/**
 * Description of MediaManager
 *
 * @author Kaik
 */
class MediaManager {
    
    private $name;
    protected $mediaHandlersManager;
    private $handler;
    private $mediaItem;

    /**
     * construct
     */
    public function __construct() {
        $this->name = 'KaikmediaGalleryModule';
        $this->mediaHandlersManager = new MediaHandlersManager();
    }    
    
    
    public function create($type) {
        $this->handler = $this->mediaHandlersManager->getMediaHandler($type);
        $this->mediaItem = $this->handler->getClass();
        return $this;  
    }
    
    public function getForm() { 
        return $this->handler->getFormClass();    
    }     
}
