<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Settings;

use Doctrine\Common\Collections\ArrayCollection;
use Kaikmedia\GalleryModule\Features\FeaturesCollection;
use Kaikmedia\GalleryModule\Media\MediaHandlersManager;
use Kaikmedia\GalleryModule\Settings\SettingsObject;

/**
 * Description of SettingsCollection
 *
 * @author Kaik
 */
class SettingsCollection extends ArrayCollection {
    
    
    //managers
   // protected $featuresManager;
    protected $mediaHandlersManager;

    //other
    //private $features;
    private $mediaHandlers;    
    
    /**
     * Initializes a new ArrayCollection.
     *
     * @param array $elements
     */
    public function __construct()
    {             
        //$this->featuresManager = new FeaturesManager();
        $this->mediaHandlersManager = new MediaHandlersManager();
        //
        $this->mediaHandlers = $this->mediaHandlersManager->getMediaHandlers();
         
        $elements = [];
        parent::__construct($elements);
        
    }    

    public function addDefault() {
        $default = new SettingsObject();
        $this->set($default->getName(), $default); 
        $this->addSupportedObjects();
    }    

    public function addSupportedObjects() {
        
        $users = new SettingsObject();
        $users->setName('ZikulaUsersModule');
        $users->setEnabled(0);
        $users->setDisplayName('Users');
        $users->setEntity('Zikula\UsersModule\Entity\UserEntity');
        $this->set($users->getName(), $users);
        
        $pages = new SettingsObject();
        $pages->setName('KaikmediaPagesModule');
        $pages->setEnabled(0);
        $pages->setDisplayName('Pages');
        $pages->setEntity('Kaikmedia\PagesModule\Entity\PageEntity');        
        $this->set($pages->getName(), $pages);
        
        return true;
    }   
    
    
    public function postSubmit($settings) {
        
        $global = $settings->get('KaikmediaGalleryModule'); 
        $this->set('KaikmediaGalleryModule', $global);
        foreach($this->toArray() as $key => $element ) {
            if($key === 'KaikmediaGalleryModule'){
                continue;
            }
            $newElement = $settings->get($key);
            if($newElement === null){
                $element->setEnabled(0);
                $element->features->postSubmit(false, $global->getFeatures()); 
            }
            $element->setEnabled($newElement->getEnabled());
            $element->features->postSubmit($newElement->getFeatures(),$global->getFeatures());    
            
        }
                
    }

        /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $elements = parent::toArray();     
        $array = [];
        foreach ($elements as $element) {
            if ($element instanceof SettingsObject) {
                $array[$element->getName()] = $element->toArray();
            }
        }
        
        return $array;
    }
}


 /*
    
    
    
    
    public function getSettingsForObject($object) {
        return (array_key_exists($object, $this->settings)) ? $this->settings[$object] : false;
    }

    public function setObjects() {
        $this->objects = new ArrayCollection();
        $this->objects->add($this->getGalleryObject());
        $supported = $this->getSupportedObjects();
        foreach($supported as $object){
            $this->objects->add($object);   
        }
    }
    
    public function addObject(SettingsObject $object) {
        
        
        
        $this->add($object);
    }   

    public function getObjects() {
        return $this->objects;
    }
    
    public function getFeatures() {
        return $this->features;
    }

    public function getFeatureForObject($object, $feature) {

        $object_settings = $this->getSettingsForObject($object);
        if ($object_settings === false) {
            return false;
        }
        $object_features = (array_key_exists('features', $object_settings)) ? $object_settings['features'] : false;
        if ($object_features === false) {
            return false;
        }

        $feature_settings = $object_features->filter(
                        function($entry) use ($feature) {
                    return ($entry->getName() == $feature) ? true : false;
                }
                )->first();

        return $feature_settings;
    }

    public function getAllowedMimeTypesForObject($object) {
        $allowedMimeTypes = [];

        $addMediaFeature = $this->getFeatureForObject($object, 'addmedia');
        if ($addMediaFeature === false) {
            return '';
        }
        $addMediaFeatureSettings = $addMediaFeature->getSettings();
        if ($addMediaFeatureSettings === false) {
            return '';
        }
        $mimeTypesCollection = $addMediaFeatureSettings->filter(
                function($entry) {
            return ($entry->getName() == 'mimetype' && $entry->getEnabled()) ? true : false;
        }
        );
        foreach ($mimeTypesCollection as $mimeType) {
            $allowedMimeTypes[] = $mimeType->getMimeType();
        }
        return implode(',', $allowedMimeTypes);
    }
    
    
    

    public function setSettings($settings) {    
        //objects 
        
        
        if ($features === null) {
            $features = new FeaturesCollection();
        }
        
        $defaultFeaturesCollection = $this->getFeaturesCollection();

        $objectFeatures = new FeaturesCollection();

        foreach ($defaultFeaturesCollection as $feature_object) {
            //$feature_key = indexOf($feature_object);
            $feature_object_db = $features->filter(
                            function($entry) use ($feature_object) {
                        return ($entry->getName() == $feature_object->getName()) ? true : false;
                    }
                    )->first();
            (!is_object($feature_object_db)) ? $objectFeatures->add($feature_object) : $objectFeatures->add($feature_object_db);
        }
    
        //dump();
        
        //$default = $this->objects->get($this->objects->key('KaikmediaGalleryModule'));
        //$default->mergeSettings($settings);
        $objects = $this->objects;
        
        
        foreach ($objects as $objectName => $object) {
            $object->mergeSettings($settings);
            $this->settings->set($objectName, $object);
        }
        
        return true;
    }
    
    public function getDefaultGlobalSettings() {
        
        $globalSettings = $this->getGalleryObject();
        $globalSettings['features'] = $this->setObjectFeatures();

        return $globalSettings;
    }

    public function mergeObjectSettings($object = [], $settings = []) {

        $objectSettings = [];
        if (array_key_exists('is_default', $object) && $object['is_default']) {
            return $this->globalSettings;
        }
        //basic
        $objectSettings['name'] = $object['name'];
        $objectSettings['display_name'] = $object['display_name'];
        //supported enabled
        $class = '\\Kaikmedia\\GalleryModule\\Entity\\Relations\\' . $objectSettings['name'] . 'RelationsEntity';
        $objectSettings['is_supported'] = (class_exists($class)) ? true : false;
        if ($objectSettings['is_supported'] == false) {
            $objectSettings['enabled'] = 0;
        } else {
            $objectSettings['enabled'] = (isset($settings['enabled']) && $settings['enabled'] == 1 && $this->globalSettings['enabled'] == 1) ? 1 : 0;
        }

        $objectSettings['entity'] = (array_key_exists('entity', $object)) ? $object['entity'] : false;

        $features = array_key_exists('features', $settings) ? $settings['features'] : null;
        $objectSettings['features'] = $this->mergeObjectFeatures($features);

        return $objectSettings;
    }

    public function mergeObjectFeatures($features = null) {

        if ($features === null) {
            $features = new FeaturesCollection();
        }
        $defaultFeaturesCollection = $this->globalSettings['features'];

        $objectFeatures = new FeaturesCollection();

        foreach ($defaultFeaturesCollection as $feature_object) {
            $feature_object_db = $features->filter(
                            function($entry) use ($feature_object) {
                        return ($entry->getName() == $feature_object->getName()) ? true : false;
                    }
                    )->first();
            if (is_object($feature_object_db)) {
                $objectFeatures->add($feature_object_db->mergeSettings($feature_object));
            } else {
                $objectFeatures->add($feature_object);
            }
        }

        return $objectFeatures;
    }    
    
    
    
    
*/