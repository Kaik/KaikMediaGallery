<?php

/**
 *
 */

namespace Kaikmedia\GalleryModule\Settings;

use Zikula\ExtensionsModule\Api\VariableApi;
use Doctrine\Common\Collections\ArrayCollection;
use Kaikmedia\GalleryModule\Features\FeaturesManager;

class SettingsManager {

    private $name;
    private $displayName;
    protected $variablesManager;
    protected $featuresManager;
    private $settings;
    private $modules;
    private $features;

    /**
     * construct
     */
    public function __construct(VariableApi $variablesManager) {
        $this->name = 'KaikmediaGalleryModule';
        $this->displayName = 'KMGallery';
        $this->setModules($this->getHookableModules());
        $this->variablesManager = $variablesManager;
        $this->featuresManager = new FeaturesManager();
        //now we have all features with default settings as array collection
        $this->setFeatures($this->featuresManager->getFeatures());
        $this->setSettings($this->variablesManager->getAll($this->name));
    }

    public function getModules() {
        return $this->modules;
    }

    public function setModules($modules) {
        //@todo: this one always on top
        $modules = ['KaikmediaGalleryModule' => $modules['KaikmediaGalleryModule']] + $modules;
        $this->modules = $modules;
    }

    /*
     * Returns settings for module 
     */

    public function setModuleSettings($moduleFullName = null, $moduleDisplayName = null, $settings = []) {

        $moduleSettings = [];
        $moduleSettings['name'] = $moduleFullName = $moduleFullName === null ? $this->name : $moduleFullName;
        $moduleSettings['display_name'] = $moduleDisplayName === null ? $this->displayName : $moduleDisplayName;
        $is_this_default_module = ($this->name == $moduleSettings['name']) ? 1 : 0;
        if ($is_this_default_module == 0) {
            $class = '\\Kaikmedia\\GalleryModule\\Entity\\Relations\\'. $moduleFullName . 'RelationsEntity';
            $moduleSettings['is_supported'] = (class_exists($class)) ? 1 : 0 ;
        } else {
            $moduleSettings['is_supported'] = 1;
        }
        if ($moduleSettings['is_supported'] == false) {
            $enabled = 0;
        } else {
            $enabled = (isset($settings['enabled']) && $settings['enabled'] == 1 ) ? 1 : $is_this_default_module;
        }
        $moduleSettings['enabled'] = $enabled;
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

    /**
     * This function get hookable modules list from system
     * 
     */
    public function getHookableModules() {

        //@todo: change it to Core 2.0 version
        $hookSubscribers = \HookUtil::getHookSubscribers();
        foreach ($hookSubscribers as $module) {
            $modules[$module['name']] = $module['displayname'];
        }
        return $modules;
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
        foreach ($modules as $moduleFullName => $moduleDisplayName) {
            $moduleSettings = array_key_exists($moduleFullName, $settings) ? $settings[$moduleFullName] : [];
            $mixedSettings[$moduleFullName] = $this->setModuleSettings($moduleFullName, $moduleDisplayName, $moduleSettings);
        }
        $this->settings = $mixedSettings;
        
        return true;
    }

    public function saveSettings() {
        return $this->variablesManager->setAll($this->name, $this->settings);
    }

    public function setSettingsFromForm($data) {
        $settings = [];
        foreach ($data as $key => $module) {
            $moduleFullName = $module['name'];
            $settings[$moduleFullName] = $module;
        }

        return $this->setSettings($settings);
    }

}
