<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule;

//use HookUtil;
use ModUtil;
use Zikula\Component\HookDispatcher\SubscriberBundle;

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
        /* Not used at the moment - initial state
        $meta['capabilities'] = array(
            HookUtil::SUBSCRIBER_CAPABLE => array(
                'enabled' => true
            )
        );
        */
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

    /** Not used for inital state
     * Define the hook bundles supported by this module.
     * 
     * @return void
     
    protected function setupHookBundles()
    {
        $bundle = new SubscriberBundle($this->name, 'subscriber.kaikmediapages.ui_hooks.pages', 'ui_hooks', $this->__('Pages Hooks'));
        $bundle->addEvent('display_view', 'kaikmediapages.ui_hooks.pages.display_view');
        $bundle->addEvent('form_edit', 'kaikmediapages.ui_hooks.pages.form_edit');
        $bundle->addEvent('form_delete', 'kaikmediapages.ui_hooks.pages.form_delete');
        $bundle->addEvent('validate_edit', 'kaikmediapages.ui_hooks.pages.validate_edit');
        $bundle->addEvent('validate_delete', 'kaikmediapages.ui_hooks.pages.validate_delete');
        $bundle->addEvent('process_edit', 'kaikmediapages.ui_hooks.pages.process_edit');
        $bundle->addEvent('process_delete', 'kaikmediapages.ui_hooks.pages.process_delete');
        $this->registerHookSubscriberBundle($bundle);
        // Post Filter Hooks
        $bundle4 = new SubscriberBundle($this->name, 'subscriber.kaikmediapages.filter_hooks.post', 'filter_hooks', $this->__('Pages page filter'));
        $bundle4->addEvent('filter', 'kaikmediapages.filter_hooks.pages.filter');
        $this->registerHookSubscriberBundle($bundle4);
    }
    */
}