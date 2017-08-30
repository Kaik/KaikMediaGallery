<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright (C) 2017 KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

//
//namespace Kaikmedia\GalleryModule\Container;
//
//use Zikula\Component\HookDispatcher\AbstractContainer;
//use Zikula\Component\HookDispatcher\SubscriberBundle;
//use Zikula\Component\HookDispatcher\ProviderBundle;
//
//class HookContainer extends AbstractContainer {
//
//    /**
//     * Define the hook bundles supported by this module.
//     *
//     * @return void
//     */
//    protected function setupHookBundles() {
//        $bundle = new SubscriberBundle('KaikmediaGalleryModule', 'subscriber.kaikmediagallery.ui_hooks.media', 'ui_hooks', $this->__('Media Hooks'));
//        $bundle->addEvent('display_view', 'kaikmediagallery.ui_hooks.media.display_view');
//        $bundle->addEvent('form_edit', 'kaikmediagallery.ui_hooks.media.form_edit');
//        $bundle->addEvent('form_delete', 'kaikmediagallery.ui_hooks.media.form_delete');
//        $bundle->addEvent('validate_edit', 'kaikmediagallery.ui_hooks.media.validate_edit');
//        $bundle->addEvent('validate_delete', 'kaikmediagallery.ui_hooks.media.validate_delete');
//        $bundle->addEvent('process_edit', 'kaikmediagallery.ui_hooks.media.process_edit');
//        $bundle->addEvent('process_delete', 'kaikmediagallery.ui_hooks.media.process_delete');
//        $this->registerHookSubscriberBundle($bundle);
//
//
//        $bundle = new SubscriberBundle('KaikmediaGalleryModule', 'subscriber.kaikmediagallery.filter_hooks.mediafilter', 'filter_hooks', $this->__('Gallery Filter Hooks'));
//        $bundle->addEvent('filter', 'kaikmediagallery.filter_hooks.media.filter');
//        $this->registerHookSubscriberBundle($bundle);
//
//
//        $bundle = new ProviderBundle('KaikmediaGalleryModule', 'provider.kaikmediagallery.ui_hooks.media', 'ui_hooks', $this->__('KMGallery media provider'));
//        $bundle->addServiceHandler('display_view', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'uiView', 'kaikmediagallery.hooks.media');
//        $bundle->addServiceHandler('form_edit', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'uiEdit', 'kaikmediagallery.hooks.media');
//        $bundle->addServiceHandler('form_delete', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'uiDelete', 'kaikmediagallery.hooks.media');
//        $bundle->addServiceHandler('validate_edit', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'validateEdit', 'kaikmediagallery.hooks.media');
//        $bundle->addServiceHandler('validate_delete', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'validateDelete', 'kaikmediagallery.hooks.media');
//        $bundle->addServiceHandler('process_edit', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'processEdit', 'kaikmediagallery.hooks.media');
//        $bundle->addServiceHandler('process_delete', 'Kaikmedia\GalleryModule\Hook\MediaHandlers', 'processDelete', 'kaikmediagallery.hooks.media');
//        $this->registerHookProviderBundle($bundle);
//    }
//
//}
