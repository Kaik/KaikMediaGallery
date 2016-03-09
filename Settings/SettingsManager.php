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
    //settings collection
    private $settings;


    /**
     * construct
     */
    public function __construct(VariableApi $variablesManager) {
        $this->name = 'KaikmediaGalleryModule';
        $this->displayName = 'KMGallery';
        $this->settings = new SettingsCollection();  
        //manager
        $this->variablesManager = $variablesManager;
        $dbSettings = $this->variablesManager->getAll($this->name);
        //dump($dbSettings);
        if(is_array($dbSettings) && !empty($dbSettings)){
            $this->settings->clear();
            $global = $dbSettings[$this->name];
            unset($dbSettings[$this->name]);
            $this->settings->set($this->name, $global);
            foreach($dbSettings as $settingObject){
                $this->settings->set($settingObject->getName(), $settingObject);
            }            
        }else {
        //settings init      
        $this->settings->addDefault();            
        }         
    }
    
    public function setSettings(SettingsCollection $settings) {
 
        if(!$settings->containsKey($this->name)){
           return false; 
        }
        
        $this->settings->postSubmit($settings);
                   
        return true;
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
        return $this->variablesManager->setAll($this->name, $this->settings->toArrayForStorage());
    }
}
