<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule;

use HookUtil;
use ModUtil;
use Zikula\Component\HookDispatcher\SubscriberBundle;
use Zikula\Component\HookDispatcher\ProviderBundle;

class GalleryModuleVersion extends \Zikula_AbstractVersion
{

    public function getMetaData()
    {
        $meta = array();
        $meta['displayname'] = $this->__('Gallery');
        $meta['description'] = $this->__('Gallery module for Zikula');
        $meta['url'] = $this->__('kaikmediagallery');
        $meta['version'] = '1.0.0';
        $meta['core_min'] = '1.4.0';
        $meta['securityschema'] = array(
            'KaikmediaGalleryModule::' => '::'
        );
        // Not used at the moment - initial state
        $meta['capabilities'] = array(
            HookUtil::SUBSCRIBER_CAPABLE => array('enabled' => true),
            HookUtil::PROVIDER_CAPABLE => array('enabled' => true),
        );
       
        // Initial state will be changed later
        $meta['securityschema'] = array(
            'KaikmediaGalleryModule::' => 'Gallery Name::Image ID'
        );
        /*
        // Module depedencies not used at the moment
        $meta['dependencies'] = array(
            array(
                'modname' => 'Scribite',
                'minversion' => '4.3.0',
                'maxversion' => '',
                'status' => ModUtil::DEPENDENCY_RECOMMENDED
            )
        );
        */
        return $meta;
    }

    /** 
     * Define the hook bundles supported by this module.
     * 
     * @return void
    */     
    protected function setupHookBundles()
    {
        $bundle = new ProviderBundle($this->name, 'provider.kaikmediagallery.ui_hooks.media', 'ui_hooks', $this->__('KMGallery media provider'));
        $bundle->addServiceHandler('display_view', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'uiView', 'kaikmediagallery.hooks.media');
        $bundle->addServiceHandler('form_edit', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'uiEdit', 'kaikmediagallery.hooks.media');
        $bundle->addServiceHandler('form_delete', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'uiDelete', 'kaikmediagallery.hooks.media');
        $bundle->addServiceHandler('validate_edit', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'validateEdit', 'kaikmediagallery.hooks.media');
        $bundle->addServiceHandler('validate_delete', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'validateDelete', 'kaikmediagallery.hooks.media');
        $bundle->addServiceHandler('process_edit', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'processEdit', 'kaikmediagallery.hooks.media');
        $bundle->addServiceHandler('process_delete', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'processDelete', 'kaikmediagallery.hooks.media');
        $this->registerHookProviderBundle($bundle);
    }

}