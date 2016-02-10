<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Media;

/**
 * Description of MediaManager
 *
 * @author Kaik
 */
class MediaHandlersManager {
    
    private $mediaHandlers;
    private $supportedMimeTypes;

    /**
     * construct
     */
    public function __construct() {

        $this->mediaHandlers = ['image', 'pdf', 'youtube', 'url'];
        $this->supportedMimeTypes = $this->getSupportedMimeTypesArray();
    }    

    public function getMediaHandlers() {
        return $this->mediaHandlers;
    }   
    
    
    public function getSupportedMimeTypes() {
        return $this->supportedMimeTypes;
    }      
   
    public function getSupportedMimeTypesArray() {    
        $supportedMimeTypes = [];
        foreach($this->mediaHandlers as $mediaHandlerAlias){
            $handler = $this->getMediaHandler($mediaHandlerAlias);
            $mimeTypes = $handler->getSupportedMimeTypes();
            foreach ($mimeTypes as $mimeType => $data){
                $supportedMimeTypes[$mimeType] = $data;    
            } 
        }
        return $supportedMimeTypes;
    }    
    
    public function getMediaHandlerClass($mediaHandlerAlias) {
        $class = 'Kaikmedia\\GalleryModule\\Media\\Handlers\\' . ucfirst($mediaHandlerAlias) . 'Handler';
        return $class;
    }     
    
    
    public function getMediaHandler($mediaHandlerAlias) {
        $class = $this->getMediaHandlerClass($mediaHandlerAlias);
        $handler = new $class();
        return $handler;
    }  
    
}
