<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Features;

use Kaikmedia\GalleryModule\Features\AbstractFeature;
use Doctrine\Common\Collections\ArrayCollection;
use Kaikmedia\GalleryModule\Media\MediaHandlersManager;
use Kaikmedia\GalleryModule\Settings\UploadSettings;
use Kaikmedia\GalleryModule\Settings\MimeTypeSettings;

/**
 * Description of IconFeature
 *
 * @author Kaik
 */
class AddmediaFeature extends AbstractFeature {
    /*

     */

    public function __construct() {
        parent::__construct();
        $this->name = 'addmedia';
        $this->type = 'origin';
        $this->enabled = 0;
        $this->icon = 'fa fa-plus';
       // $this->setDefaultSettings();
    }

    public function getDisplayName() {
        return 'Add media';
    }



    public function mergeSettings($global_settings) {
        $enabled = ($global_settings->getEnabled() == 1) ? $this->getEnabled() : 0;
        $this->setEnabled($enabled);
        $thisGlobalSettings = $global_settings->getSettings();
//        dump($thisGlobalSettings);
        if ($thisGlobalSettings instanceof ArrayCollection) {
            foreach ($this->settings as $setting) {
                if ($setting instanceof UploadSettings) {
                    $this->mergeUploadSetting($setting, $thisGlobalSettings);
                } elseif ($setting instanceof MimeTypeSettings) {
                    $this->mergeMimeTypeSetting($setting, $thisGlobalSettings);
                }
            }
            return $this;
        } else {
            return $this;
        }
    }

    public function mergeUploadSetting($setting, $thisGlobalSettings) {

        $globalUploadSetting = $thisGlobalSettings->filter(
                            function($entry) use ($setting) {
                        return ($entry->getName() == $setting->getName()) ? true : false;
                    }
                    )->first();

            if (is_object($globalUploadSetting)) {
                $setting->setUploadMaxFiles(($setting->getUploadMaxFiles() != '0' && $setting->getUploadMaxFiles() < $globalUploadSetting->getUploadMaxFiles() ? $setting->getUploadMaxFiles() : $globalUploadSetting->getUploadMaxFiles()));
                $setting->setUploadMaxSingleSize(($setting->getUploadMaxSingleSize() != '0' && $setting->getUploadMaxSingleSize() < $globalUploadSetting->getUploadMaxSingleSize() ? $setting->getUploadMaxSingleSize() : $globalUploadSetting->getUploadMaxSingleSize()));
            }
    }

    public function mergeMimeTypeSetting($setting, $thisGlobalSettings) {

                $globalMimeTypesSetting = $thisGlobalSettings->filter(
                            function($entry) use ($setting) {
                        return ($entry->getName() == $setting->getName() && $entry->getMimeType() == $setting->getMimeType() ) ? true : false;
                    }
                    )->first();
            if (is_object($globalMimeTypesSetting )) {
                $setting->setEnabled(($globalMimeTypesSetting->getEnabled() == 1 ? $setting->getEnabled() : $globalMimeTypesSetting->getEnabled()));
            }
    }

    public function setDefaultSettings() {

        $uploadSettings = new UploadSettings();
        $this->settings->add($uploadSettings);
        $mediaHandlersManager = new MediaHandlersManager();
        $mimeTypes = $mediaHandlersManager->getSupportedMimeTypes();
        foreach ($mimeTypes as $mimeType => $data) {
            $mimeTypeSettings = new MimeTypeSettings();
            $mimeTypeSettings->setMimeType($mimeType);
            $mimeTypeSettings->setHandler($data['handler']);
            $this->settings->add($mimeTypeSettings);
        }
    }

}
