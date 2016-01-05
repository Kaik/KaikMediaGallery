<?php

/**
 *
 */

namespace Kaikmedia\GalleryModule\Manager;

use Zikula\ExtensionsModule\Api\VariableApi;
use Doctrine\Common\Collections\ArrayCollection;
use Kaikmedia\GalleryModule\Features\FeaturesManager;

class SettingsManager {

    private $name;
    protected $variablesManager;
    protected $featuresManager;    
    private $settings;
    private $modules;

    /**
     * construct
     */
    public function __construct(VariableApi $variablesManager) {
        $this->name = 'KaikmediaGalleryModule';
        $this->variablesManager = $variablesManager;
        $this->settings = $this->variablesManager->getAll($this->name);
        $this->setHookableModules();
        $this->featuresManager = new FeaturesManager($this);
    }

    public function setHookableModules() {
        $hookSubscribers = \HookUtil::getHookSubscribers();
        $modules = [];
        foreach ($hookSubscribers as $module) {
            $modules[$module['name']] = $module['displayname'];
        }
        //this one always on top
        $modules = ['KaikmediaGalleryModule' => $modules['KaikmediaGalleryModule']] + $modules;

        $this->modules = $modules;        
    }

    public function getModules() {
        $this->modules;
    }  
    
    public function getActiveModules() {
        
    }     
    
    public function getModuleSettings($module) {
        
    }
    
    public function getSettingsForForm() {
        
        $modules = $this->modules;
        $settings = [];
        foreach($modules as $moduleFullName => $moduleDisplayName){
            $settings[] = ['name' => $moduleFullName, 'enabled' => 1, 'features' => $this->addFeatures()];
        }
        
        return $settings;
    } 
    
    public function addFeatures() {  
        $installedFeatures = $this->featuresManager->getFeatures();

        $features = new ArrayCollection();        
        foreach($installedFeatures as $featureAlias){        
        $features->add( $this->featuresManager->getFeature($featureAlias));        
        }
        return $features;
    }    

    public function getGallerySettings() {  
        $gallerySettings = [];
        
        return $gallerySettings;
    }     
    
}