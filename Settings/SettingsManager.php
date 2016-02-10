<?php

/**
 *
 */

namespace Kaikmedia\GalleryModule\Settings;

use Zikula\ExtensionsModule\Api\VariableApi;
use Kaikmedia\GalleryModule\Settings\SettingsCollection;

class SettingsManager {

    //defaults
    private $name;
    private $displayName;
    //managers
    protected $variablesManager;
    //settings
    private $settings;


    /**
     * construct
     */
    public function __construct(VariableApi $variablesManager) {
        $this->name = 'KaikmediaGalleryModule';
        $this->displayName = 'KMGallery';

        //managers
        $this->variablesManager = $variablesManager;
        //settings init
        $this->settings = new SettingsCollection();        
        $this->settings->addDefault();
        
        $dbSettings = $this->variablesManager->getAll($this->name);
        
        if(is_array($dbSettings)){
            $this->settings->clear();
            foreach($dbSettings as $settingObject){
                $this->settings->addObject($settingObject);
            }            
        }else {
            
        }
    }
    
    public function setSettings(SettingsCollection $settings) {
        
      
        
        return $this->settings = $settings;
    }    

    public function getSettings() {
        return $this->settings;
    }
    
    public function getSettingsArray() {
        
        $array = $this->settings->toArray();
        return $array;
    }
   

    public function getSettingsForForm() {
        return ['settings' => $this->settings];
    }
    
    public function saveSettings() {
        return $this->variablesManager->setAll($this->name, $this->settings->toArray());
    }
}
