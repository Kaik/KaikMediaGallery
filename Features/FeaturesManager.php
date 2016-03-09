<?php

/**
 *
 */

namespace Kaikmedia\GalleryModule\Features;

class FeaturesManager {

    private $features;

    /**
     * construct
     */
    public function __construct() {
        $this->features = ['info', 'icon', 'featured', 'additional', 'user', 'public', 'album', 'gallery', 'insert', 'avatar', 'addmedia'];     
        //$this->getFeaturesCollection();
    }

    public function getFeatures() {
        return $this->features;
    }

    public function getFeatureClass($featureAlias) {
        $class = 'Kaikmedia\\GalleryModule\\Features\\' . ucfirst($featureAlias) . 'Feature';
        return $class;
    }

    public function getFeature($featureAlias) {
        $class = $this->getFeatureClass($featureAlias);
        $feature = new $class();
        return $feature;
    }
}
