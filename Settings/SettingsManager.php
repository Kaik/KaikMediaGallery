<?php

/**
 *
 */

namespace Kaikmedia\GalleryModule\Settings;

use Zikula\ExtensionsModule\Api\VariableApi;
use Doctrine\Common\Collections\ArrayCollection;
use Kaikmedia\GalleryModule\Features\FeaturesManager;
use Kaikmedia\GalleryModule\Media\MediaHandlersManager;

class SettingsManager {

    //defaults
    private $name;
    private $displayName;
    //managers
    protected $variablesManager;
    protected $featuresManager;
    protected $mediaHandlersManager;
    //settings
    private $settings;
    //
    private $globalSettings;
    //objects    
    private $objects;
    //other
    private $features;
    private $mediaHandlers;

    /**
     * construct
     */
    public function __construct(VariableApi $variablesManager) {
        $this->name = 'KaikmediaGalleryModule';
        $this->displayName = 'KMGallery';

        // supported objects
        $this->setObjects($this->getSupportedObjects());

        //managers
        $this->variablesManager = $variablesManager;
        $this->featuresManager = new FeaturesManager();
        $this->mediaHandlersManager = new MediaHandlersManager();
        //now we have all features with default settings as array collection

        $this->setFeatures($this->featuresManager->getFeatures());
        $this->mediaHandlers = $this->mediaHandlersManager->getMediaHandlers();

        // set
        $this->setSettings($this->variablesManager->getAll($this->name));
    }

    public function getSettings() {
        return $this->settings;
    }

    public function getSettingsForForm() {
        $settingsArray = [];
        foreach ($this->settings as $object) {
            $settingsArray[] = $object;
        }
        return $settingsArray;
    }

    public function setSettings($settings = []) {
        $mixedSettings = [];
        $objects = $this->objects;

        $this->globalSettings = array_key_exists($this->name, $settings) ? $settings[$this->name] : $this->getDefaultGlobalSettings();

        foreach ($objects as $objectName => $object) {
            //default if new object
            $objectSettings = array_key_exists($objectName, $settings) ? $settings[$objectName] : [];
            //merge
            $mixedSettings[$objectName] = $this->mergeObjectSettings($object, $objectSettings);
        }

        $this->settings = $mixedSettings;

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

    /*
     * Features
     */

    public function mergeObjectFeatures($features = null) {

        if ($features === null) {
            $features = new ArrayCollection();
        }
        $defaultFeaturesCollection = $this->globalSettings['features'];

        $objectFeatures = new ArrayCollection();

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

    public function saveSettings() {
        return $this->variablesManager->setAll($this->name, $this->settings);
    }

    public function setSettingsFromForm($data) {
        $settings = [];
        foreach ($data as $object) {
            $settings[$object['name']] = $object;
        }

        return $this->setSettings($settings);
    }

    public function getSettingsForObject($object) {
        return (array_key_exists($object, $this->settings)) ? $this->settings[$object] : false;
    }

    public function setObjects($objects) {
        $this->objects = $this->getGalleryObject() + $objects;
    }

    public function getObjects() {
        return $this->objects;
    }

    /*
     * Features
     */

    public function setObjectFeatures($features = null) {

        if ($features === null) {
            $features = new ArrayCollection();
        }
        $defaultFeaturesCollection = $this->getFeaturesCollection();

        $objectFeatures = new ArrayCollection();

        foreach ($defaultFeaturesCollection as $feature_object) {
            //$feature_key = indexOf($feature_object);
            $feature_object_db = $features->filter(
                            function($entry) use ($feature_object) {
                        return ($entry->getName() == $feature_object->getName()) ? true : false;
                    }
                    )->first();
            (!is_object($feature_object_db)) ? $objectFeatures->add($feature_object) : $objectFeatures->add($feature_object_db);
        }

        return $objectFeatures;
    }

    public function getFeatures() {
        return $this->features;
    }

    public function setFeatures($new_features) {
        $this->features = $new_features;
    }

    public function getFeaturesCollection() {
        $features = new ArrayCollection();
        $featuresList = $this->features;
        foreach ($featuresList as $feature) {
            $features->add($this->featuresManager->getFeature($feature));
        }

        return $features;
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

    public function getGalleryObject() {
        return [
            'KaikmediaGalleryModule' => ['name' => 'KaikmediaGalleryModule',
                'is_default' => true, //mark this module unused
                'display_name' => 'Gallery',
                'is_supported' => 1,
                'enabled' => 1,
        ]];
    }

    public function getSupportedObjects() {
        return [
            'ZikulaUsersModule' => ['name' => 'ZikulaUsersModule',
                'display_name' => 'Users',
                'is_supported' => 1,
                'enabled' => 0,
                'entity' => 'Zikula\UsersModule\Entity\UserEntity'
            ],
            'KaikmediaPagesModule' => ['name' => 'KaikmediaPagesModule',
                'display_name' => 'Pages',
                'is_supported' => 1,
                'enabled' => 0,
                'entity' => 'Kaikmedia\PagesModule\Entity\PageEntity'
            ]
        ];
    }

}
