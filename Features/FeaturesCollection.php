<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Features;

use Doctrine\Common\Collections\ArrayCollection;
use Kaikmedia\GalleryModule\Features\FeaturesManager;

/**
 * Description of FeaturesCollection
 *
 * @author Kaik
 */
class FeaturesCollection extends ArrayCollection {
    //put your code here
    
    
    /**
     * Initializes a new ArrayCollection.
     *
     * @param array $elements
     */
    public function __construct()
    {             
        $elements = [];
        parent::__construct($elements);   
        
        $featuresManager = new FeaturesManager();
        $features = $featuresManager->getFeatures();
         foreach($features as $featureAlias){
          $this->set($featureAlias ,$featuresManager->getFeature($featureAlias));  
         }   
    }     
    
    public function postSubmit($new, $global) {

        $elements = parent::toArray();

        foreach($elements as $key => $element ) {
            
            $globalFeatureData = $global->get($key);
            
            if($new === null){
                $element->setEnabled(0);
                $element->settings->postSubmit(false, $globalFeatureData->getSettings()); 
                continue;
            }
            $newFeatureData = $new->get($key);
            $enabled = $globalFeatureData->getEnabled() === 1 ? $newFeatureData->getEnabled() : 0;  
            $element->setEnabled($enabled);       
            $element->settings->postSubmit($newFeatureData->getSettings(), $globalFeatureData->getSettings());    
            
        }        

    }    

    
    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $elements = parent::toArray();
        $array = [];
        foreach ($elements as $key => $element) {
            if ($element instanceof AbstractFeature) {
                $array[$key] = $element->toArray();
            }
        }
        return $array;
    }
    
}
        
        

        /*
        $featuresMappedSettings  = $this->map(
            function($entry) use ($global) {  
            
                $globalFeature = $global->get($entry->getName());
                if( $globalFeature === null ) {
                    //no global settings for this entry
                    return false;
                }  
                $enabled = $globalFeature->getEnabled() == 1 ? $entry->getEnabled() : 0 ;
                $entry->setEnabled($enabled);
                //map settings
                $mappedFeatureSettings = $entry->settings->postSubmit($globalFeature->getSettings());
                if($mappedFeatureSettings instanceof FeatureSettingsCollection){
                    $entry->setSettings($mappedFeatureSettings);
                    return true;
                }else{
                    return false;
                }  
            }
        );

        return $featuresMappedSettings;
         * 
         */