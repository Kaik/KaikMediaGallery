<?php

/**
 *
 */

namespace Kaikmedia\GalleryModule\Manager;

use Zikula\ExtensionsModule\Api\VariableApi;

class SettingsManager {

    private $name;
    protected $variablesManager;
    private $settings;
    private $modules;

    /**
     * construct
     */
    public function __construct(VariableApi $variablesManager) {
        $this->name = 'KaikmediaGalleryModule';
        $this->variablesManager = $variablesManager;
        $this->settings = $this->variablesManager->getAll($this->name);
        $this->modules = $this->getHookableModules();
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

    public function getGallerySettings() {  
        $gallerySettings = [];
        
        return $gallerySettings;
    }     
    
}