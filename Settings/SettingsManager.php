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

    private $name;
    private $displayName;
    protected $variablesManager;
    protected $featuresManager;
    protected $mediaHandlersManager;
    private $settings;
    private $modules;
    private $features;
    private $mediaHandlers;

    /**
     * construct
     */
    public function __construct(VariableApi $variablesManager) {
        $this->name = 'KaikmediaGalleryModule';
        $this->displayName = 'KMGallery';
        $this->setModules($this->getSupportedObjects());
        $this->variablesManager = $variablesManager;
        $this->featuresManager = new FeaturesManager();
        $this->mediaHandlersManager = new MediaHandlersManager();
        //now we have all features with default settings as array collection
        $this->setFeatures($this->featuresManager->getFeatures());
        $this->mediaHandlers = $this->mediaHandlersManager->getMediaHandlers();
        $this->setSettings($this->variablesManager->getAll($this->name));
    }

    public function getModules() {
        return $this->modules;
    }

    public function getModulesMetaData() {

        //$test = [];
        $modules = $this->entityManager->getRepository('Zikula\Core\Doctrine\Entity\ExtensionEntity')->findBy(array('state' => 3), array('displayname' => 'ASC'));

        return $modules;
    }

    public function setModules($modules) {
        $this->modules = ['KaikmediaGalleryModule' => $modules['KaikmediaGalleryModule']] + $modules;
    }

    /*
     * Returns settings for module 
     */

    public function setModuleSettings($moduleFullName = null, $module = [], $settings = []) {

        $moduleSettings = [];
        $moduleSettings['name'] = $moduleFullName = $moduleFullName === null ? $this->name : $moduleFullName;
        $moduleSettings['display_name'] = $module['display_name'] === null ? $this->displayName : $module['display_name'];
        $is_this_default_module = ($this->name == $moduleSettings['name']) ? 1 : 0;
        if ($is_this_default_module == 0) {
            $class = '\\Kaikmedia\\GalleryModule\\Entity\\Relations\\' . $moduleFullName . 'RelationsEntity';
            $moduleSettings['is_supported'] = (class_exists($class)) ? 1 : 0;
        } else {
            $moduleSettings['is_supported'] = 1;
        }
        if ($moduleSettings['is_supported'] == false) {
            $enabled = 0;
        } else {
            $enabled = (isset($settings['enabled']) && $settings['enabled'] == 1 ) ? 1 : $is_this_default_module;
        }
        $moduleSettings['enabled'] = $enabled;
        $moduleSettings['entity'] = (array_key_exists('entity', $module)) ? $module['entity'] : false;
        $features = array_key_exists('features', $settings) ? $settings['features'] : null;
        $moduleSettings['features'] = $this->setModuleFeatures($features);

        return $moduleSettings;
    }

    /*
     * 
     */

    public function setModuleFeatures($features = null) {

        if ($features === null) {
            $features = new ArrayCollection();
        }
        $defaultFeaturesCollection = $this->getFeaturesCollection();

        $moduleFeatures = new ArrayCollection();

        foreach ($defaultFeaturesCollection as $feature_object) {
            //$feature_key = indexOf($feature_object);
            $feature_object_db = $features->filter(
                            function($entry) use ($feature_object) {
                        return ($entry->getName() == $feature_object->getName()) ? true : false;
                    }
                    )->first();
            (!is_object($feature_object_db)) ? $moduleFeatures->add($feature_object) : $moduleFeatures->add($feature_object_db);
        }

        return $moduleFeatures;
    }

    public function getFeatures() {
        return $this->features;
    }

    public function setFeatures($new_features) {
        $this->features = $new_features;
    }

    public function getFeaturesCollection() {
        $moduleFeatures = new ArrayCollection();
        $features_list = $this->features;
        foreach ($features_list as $feature) {
            $moduleFeatures->add($this->featuresManager->getFeature($feature));
        }

        return $moduleFeatures;
    }

    public function getSettings() {
        return $this->settings;
    }

    public function getSettingsForForm() {
        $settingsArray = [];
        foreach ($this->settings as $module) {
            $settingsArray[] = $module;
        }
        return $settingsArray;
    }

    public function setSettings($settings = []) {
        $mixedSettings = [];
        $modules = $this->modules;
        foreach ($modules as $moduleFullName => $module) {
            $moduleSettings = array_key_exists($moduleFullName, $settings) ? $settings[$moduleFullName] : [];
            $mixedSettings[$moduleFullName] = $this->setModuleSettings($moduleFullName, $module, $moduleSettings);
        }
        $this->settings = $mixedSettings;

        return true;
    }

    public function saveSettings() {
        return $this->variablesManager->setAll($this->name, $this->settings);
    }

    public function setSettingsFromForm($data) {
        $settings = [];
        foreach ($data as $module) {
            $moduleFullName = $module['name'];
            $settings[$moduleFullName] = $module;
        }

        return $this->setSettings($settings);
    }

    public function getSettingsForObject($object) {
        return (array_key_exists($object, $this->settings)) ? $this->settings[$object] : false;
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
        foreach ($mimeTypesCollection as $mimeType){
          $allowedMimeTypes[] = $mimeType->getMimeType();
        }    
        return implode(',', $allowedMimeTypes);
    }

    public function getSupportedObjects() {
        $supportedObjects = [
            'KaikmediaGalleryModule' => ['name' => 'KaikmediaGalleryModule',
                'is_default' => true, //mark this module unused
                'display_name' => 'Gallery',
                'is_supported' => 1,
                'enabled' => 1,
            ],
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
        
        return $supportedObjects;
    }

}
