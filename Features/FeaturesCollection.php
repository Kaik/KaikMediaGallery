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
          $this->add($featuresManager->getFeature($featureAlias));  
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
