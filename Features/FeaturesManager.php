<?php

/**
 *
 */

namespace Kaikmedia\GalleryModule\Features;

use Kaikmedia\GalleryModule\Manager\SettingsManager;

class FeaturesManager {

    private $name;
    protected $settingsManager;
    private $features;

    /**
     * construct
     */
    public function __construct(SettingsManager $settingsManager) {
        $this->name = 'KaikmediaGalleryModule';
        $this->settingsManager = $settingsManager;
        $this->features = ['gallery', 'icon'];//, 'featured', 'additional', 'upload', 'user', 'public', 'album', 'insert'];
    }    
    
    public function getFeatures() {
        return $this->features;
    }    
    
    public function getFeature($featureAlias) {
        $class = 'Kaikmedia\\GalleryModule\\Features\\' . ucfirst($featureAlias) . 'Feature';
        $feature = new $class();
        return $feature;
    }     
    
}