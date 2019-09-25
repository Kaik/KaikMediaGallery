<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Collector;

use Kaikmedia\GalleryModule\Media\Handlers\IdentityMediaHandler;
use Kaikmedia\GalleryModule\Media\Handlers\MediaHandlerInterface;
use Kaikmedia\GalleryModule\Settings\MimeTypeSettings;

/**
 * Class MediaHandlersCollector
 */
class MediaHandlersCollector
{
    /**
     * @var MediaHandlerInterface[] e.g. [<handlerName> => <ServiceObject>]
     */
    private $mediaHandlers = [];

    /**
     * Add a service to the collection.
     * @param string $moduleName
     * @param TopicHookedModuleInterface $service
     */
    public function add($handlerName, MediaHandlerInterface $service)
    {
        if (isset($this->mediaHandlers[$handlerName])) {
            throw new \InvalidArgumentException('Attempting to register a duplicate topic hooked module name. (' . $handlerName . ')');
        }
        $this->mediaHandlers[$handlerName] = $service;
    }

    /**
     * Get a MediaHandlerInterface from the collection by moduleName.
     * @param $moduleName
     * @return TopicHookedModuleInterface|null
     */
    public function get($handlerName)
    {
        return isset($this->mediaHandlers[$handlerName]) ? $this->mediaHandlers[$handlerName] : null;
    }

    /**
     * Get all the messageModules in the collection.
     * @return TopicHookedModuleInterface[]
     */
    public function getAll()
    {
        return $this->mediaHandlers;
    }

    /**
     * @return array of service aliases
     */
    public function getKeys()
    {
        return array_keys($this->mediaHandlers);
    }

    /**
     * @return IdentityMediaHandler
     */
    public function getDefault()
    {
        return new IdentityMediaHandler();
    }   
    
    public function getSupportedMimeTypesArray() {    
        $supportedMimeTypes = [];
        foreach($this->mediaHandlers as $mediaHandler) {
            $mimeTypes = $mediaHandler->getSupportedMimeTypes();
            foreach ($mimeTypes as $mimeType => $data){
                $supportedMimeTypes[$mimeType] = $data;    
            } 
        }
        
        return $supportedMimeTypes;
    }
    
    public function getMimeTypesSettingsObjects() {
        $mimeTypesSettingsCollection = [];
        foreach ($this->getSupportedMimeTypesArray() as $key => $mimeType) {
            $handler = $this->get($mimeType['handler']);
            if ($handler) {
                $obj = new MimeTypeSettings();
                $obj->setMimeType($key);
                $obj->setHandler($handler);
                $obj->setName($mimeType['name']);
                $obj->setIcon($handler->getMimeTypeIcon($key));
                $mimeTypesSettingsCollection[$obj->getName()] = $obj; 
            }
        }
        
        return $mimeTypesSettingsCollection;
    }
}
