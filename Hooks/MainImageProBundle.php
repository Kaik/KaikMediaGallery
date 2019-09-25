<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Hooks;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

use Zikula\Bundle\CoreBundle\HttpKernel\ZikulaHttpKernelInterface;
use Zikula\Bundle\HookBundle\HookProviderInterface;
use Zikula\Bundle\HookBundle\Category\UiHooksCategory;
use Zikula\Bundle\HookBundle\Hook\DisplayHook;
use Zikula\Bundle\HookBundle\Hook\DisplayHookResponse;
use Zikula\Bundle\HookBundle\Hook\Hook;
use Zikula\Bundle\HookBundle\Hook\ProcessHook;
use Zikula\Bundle\HookBundle\Hook\ValidationHook;
use Zikula\Bundle\HookBundle\ServiceIdTrait;
use Zikula\Common\Translator\TranslatorInterface;

use Kaikmedia\GalleryModule\Settings\SettingsManager;
use Kaikmedia\GalleryModule\Security\AccessManager;
use Kaikmedia\GalleryModule\Collector\MediaHandlersCollector;
use Kaikmedia\GalleryModule\Media\MediaManager;
use Kaikmedia\GalleryModule\Media\HookedMediaCollectionManager;
use Kaikmedia\GalleryModule\Entity\Relations\HooksRelationsEntity;

/**
 * MainImageProBundle
 *
 * @author Kaik
 */
class MainImageProBundle extends AbstractProBundle implements HookProviderInterface
{
    use ServiceIdTrait;

    /**
     * @var ZikulaHttpKernelInterface
     */
    private $kernel;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $requestStack;    
    
    /**
     * @var EngineInterface
     */
    protected $renderEngine;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var SettingsManager
     */
    private $settingsManager;

    /**
     * @var AccessManager
     */
    private $accessManager;
    
    /**
     * @var MediaHandlersCollector
     */
    private $mediaHandlersCollector;
    
    /**
     * @var MediaHandlersCollector
     */
    private $hookedMediaCollectionsManager;
    
    /**
     * @var MediaManager
     */
    private $mediaManager;
    
    /**
     * Construct the manager
     *
     * @param ZikulaHttpKernelInterface $kernel
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     * @param RequestStack $requestStack
     * @param EngineInterface $renderEngine
     * @param EntityManager $entityManager
     * @param SettingsManager $settingsManager
     * @param AccessManager $accessManager
     * @param MediaHandlersCollector $mediaHandlersCollector
     * @param MediaManager $mediaManager
     */
    public function __construct(
        ZikulaHttpKernelInterface $kernel,
        TranslatorInterface $translator,
        RouterInterface $router,
        RequestStack $requestStack, 
        EngineInterface $renderEngine,
        EntityManager $entityManager,
        SettingsManager $settingsManager,
        AccessManager $accessManager,
        MediaHandlersCollector $mediaHandlersCollector,
        HookedMediaCollectionManager $hookedMediaCollectionsManager,
        MediaManager $mediaManager
    ) {
        $this->setAreaName('provider.gallery.ui_hooks.main_image');
        $this->kernel = $kernel;
        $this->translator = $translator;
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getMasterRequest();
        $this->renderEngine = $renderEngine;
        $this->entityManager = $entityManager;
        $this->settingsManager = $settingsManager;
        $this->accessManager = $accessManager;
        $this->mediaHandlersCollector = $mediaHandlersCollector;
        $this->hookedMediaCollectionsManager = $hookedMediaCollectionsManager;
        $this->mediaManager = $mediaManager;
        parent::__construct();
    }

    public function getCategory()
    {
        return UiHooksCategory::NAME;
    }

    public function getTitle()
    {
        return $this->translator->__('Main image Provider');
    }

    public function getProviderTypes()
    {
        return [
            UiHooksCategory::TYPE_DISPLAY_VIEW => 'view',
            UiHooksCategory::TYPE_FORM_EDIT => 'edit',
            UiHooksCategory::TYPE_VALIDATE_EDIT => 'validateEdit',
            UiHooksCategory::TYPE_PROCESS_EDIT => 'processEdit',
            UiHooksCategory::TYPE_FORM_DELETE => 'delete',
            UiHooksCategory::TYPE_VALIDATE_DELETE => 'validateDelete',
            UiHooksCategory::TYPE_PROCESS_DELETE => 'processDelete',
        ];
    }

    public function getSettingsForm()
    {
        return 'Kaikmedia\\GalleryModule\\Form\\Type\\Hook\\Providers\\MainMediaProvider\\MainMediaProviderSettingsType';
    }

    public function getBindingForm()
    {
        return 'Kaikmedia\\GalleryModule\\Form\\Type\\Hook\\Providers\\MainMediaProvider\\MainMediaProviderBindingType';
    }

    public function parseProviderSettingsForArea($savedSettings)
    {
        $default = [
            'display_title' => 'Main media',
        ];
        $settings = array_key_exists('settings', $savedSettings) ? $savedSettings['settings'] : [];
        $default['settings']['display'] = $this->parseAreaDisplaySettings(array_key_exists('display', $settings) ? $settings['display'] : []);
        $default['settings']['edit'] = $this->parseAreaEditSettings(array_key_exists('edit', $settings) ? $settings['edit'] : []);
        
        return $default;
        
    }
    public function parseAreaDisplaySettings($displaySettings)
    {
        $fields = [
            'feature'           => '',
            'css_class'         => '',
            'width'             => 0,
            'height'            => 0,
            'mode'              => '0',
            'autoplay'          => 0,
            'show_title'        => 0,
            'show_description'  => 0,
            'show_legal'        => 0,
        ];   
        $display = [];
        foreach ($fields as $field_name => $field) {            
            $display[$field_name] = array_key_exists($field_name, $displaySettings) ? $displaySettings[$field_name] : $field;
        }
        
        $display['mimeTypes'] = $this->parseAreaEditMimeTypesSettings(array_key_exists('mimeTypes', $displaySettings) ? $displaySettings['mimeTypes'] : [] );

        
        return $display;
    }
    
    public function parseAreaEditSettings($editSettings)
    {        
        $fields = [
            'name'                              => '',
            'title'                             => '',
            'prefix'                            => '',
            'dir'                               => '',
            'multiple'                          => '0',
            'maxitems'                          => 0,
            'maxsize'                           => '',
            'plugin_item_css'                   => '',
            'preview_css_class'                 => '',
            'enable_editor'                     => '0',
            'editor_positioning'                => '0',
            'editor_rotate'                     => '0',
            'enable_info'                       => '0',
            'enable_extra'                      => '0',
            'extra_title'                       => '0',
            'extra_description'                 => '0',
            'extra_legal'                       => '0',
        ];

        $edit = [];
        foreach ($fields as $field_name => $field) {            
            $edit[$field_name] = array_key_exists($field_name, $editSettings) ? $editSettings[$field_name] : $field;
        }
        
        $edit['mimeTypes'] = $this->parseAreaEditMimeTypesSettings(array_key_exists('mimeTypes', $editSettings) ? $editSettings['mimeTypes'] : [] );
        
        return $edit;
    }
    
    public function parseAreaEditMimeTypesSettings($mimeTypesSettings)
    {        
        $mimeTypesCollection = $this->mediaHandlersCollector->getMimeTypesSettingsObjects();
        
        foreach ($mimeTypesCollection as $mimeType) {
            if (array_key_exists($mimeType->getName(), $mimeTypesSettings)) {
                $mimeType->mergeFromSettings($mimeTypesSettings[$mimeType->getName()]);
            }
        }
        
        return $mimeTypesCollection;
    }
    
    /**
     * Display hook for view.
     *
     * @param DisplayHook $hook the hook
     *
     * @return string
     */
    public function view(DisplayHook $hook)
    {
        if (!$this->accessManager->hasPermission(ACCESS_READ, false, null, null, null, null, $hook->getCaller())) {
            return;
        }
        
        $provider_config = $this->settingsManager->getProviderConfigForCaller($this->getAreaName(), $hook->getCaller(), $hook->getAreaId());
        
        if (!array_key_exists('settings', $provider_config)) {
            $content = 'disabled';
            goto display;
        }
        
        if (!array_key_exists('enabled', $provider_config)) {
//            $content = 'disabled';
//            goto display;
        }
        
        if (!array_key_exists('display', $provider_config['settings'])) {
            $content = 'disabled';
            goto display;
        }
        
        $handlers = [];
        $displaySettings = $provider_config['settings']['display'];
        foreach ($displaySettings['mimeTypes'] as $key => $mimeType) {
            $handlers[$mimeType->getHandler()->getName()] = $mimeType->getHandler();
        }
        
        $selectedCollectionManager = $this->hookedMediaCollectionsManager->buildCollection();
        $selectedCollectionManager->setHookedModule($hook->getCaller());
//        $selectedCollectionManager->setHookedAreaId($hook->getAreaId());//because it is used as a preview as well
        $selectedCollectionManager->setHookedObjectId($hook->getId());
        $selectedCollectionManager->setFeature($displaySettings['feature']);
        $selectedCollectionManager->load();   
        
        $content = $this->renderEngine->render('KaikmediaGalleryModule:Hook/MainMediaProvider:hook.view.mainmedia.html.twig', [
            'hookedModule'      => $hook->getCaller(),
            'hookedAreaId'      => $hook->getAreaId(),
            'hookedObjectId'    => $hook->getId(),
            'hookedUrlObject'   => null,
            'selectedItems'     => $selectedCollectionManager,
            'settings'          => $displaySettings,
            'handlers'          => $handlers,
            'areaName'          => $this->getAreaName()
        ]);
        
        display:

        $hook->setResponse(new DisplayHookResponse('provider.gallery.ui_hooks.main_image', $content));
    }

    /**
     * Display hook for edit.
     * Display a UI interface during the creation of the hooked object.
     *
     * @param DisplayHook $hook the hook
     *
     * @return string
     */
    public function edit(DisplayHook $hook)
    {
        if (!$this->accessManager->hasPermission(ACCESS_READ, false, null, null, null, null, $hook->getCaller())) {
            return;
        }

        $provider_config = $this->settingsManager->getProviderConfigForCaller($this->getAreaName(), $hook->getCaller(), $hook->getAreaId());
        
        if (!array_key_exists('settings', $provider_config)) {
            $content = 'disabled';
            goto display;
        }
        
        if (!array_key_exists('enabled', $provider_config)) {
//            $content = 'disabled';
//            goto display;
        }
        
        if (!array_key_exists('edit', $provider_config['settings'])) {
            $content = 'not set';
            goto display;
        }

        $handlers = [];
        $editSettings = $provider_config['settings']['edit'];
        foreach ($editSettings['mimeTypes'] as $key => $mimeType) {
            $handlers[$mimeType->getHandler()->getName()] = $mimeType->getHandler();
        }
        
        $selectedCollectionManager = $this->hookedMediaCollectionsManager->buildCollection();
        $selectedCollectionManager->setHookedModule($hook->getCaller());
        $selectedCollectionManager->setHookedAreaId($hook->getAreaId());
        $selectedCollectionManager->setHookedObjectId($hook->getId());
        $selectedCollectionManager->setFeature($editSettings['name']);
        $selectedCollectionManager->load();  
        
        $content =  $this->renderEngine->render('KaikmediaGalleryModule:Hook/MainMediaProvider:hook.edit.mainmedia.html.twig', [
                'hookedModule'      =>  $hook->getCaller(),
                'hookedAreaId'      => $hook->getAreaId(),
                'hookedObjectId'    => $hook->getId(),
                'hookedUrlObject'   => null,
                'selectedItems'     => $selectedCollectionManager,
                'settings'          => $editSettings,
                'handlers'          => $handlers,
                'areaName'          => $this->getAreaName()
        ]);
        
        display:
                    
        $hook->setResponse(new DisplayHookResponse('provider.gallery.ui_hooks.main_image', $content));
    }

    /**
     * Validate hook for edit.
     *
     * @param ValidationHook $hook the hook
     *
     * @return void (unused)
     */
    public function validateEdit(ValidationHook $hook)
    {
    }

    /**
     * Process hook for edit.
     * Collect data from post
     * Handle selected
     *
     * @param ProcessHook $hook the hook
     *
     * @return bool
     */
    public function processEdit(ProcessHook $hook)
    {
        $selected = $this->request->request->get('provider_gallery_ui_hooks_main_image');
        
        if (empty($selected)) {
            return true;
        }
        
        if (!array_key_exists($hook->getCaller(), $selected)) {
            return true;
        }

        if (!array_key_exists($hook->getAreaId(), $selected[$hook->getCaller()])) {
            return true;
        }
        
        if (!array_key_exists($hook->getAreaId(), $selected[$hook->getCaller()])) {
            return true;
        }

        $recivedAreaData = $selected[$hook->getCaller()][$hook->getAreaId()];
        if (empty($recivedAreaData)) {
            return true;
        }
        
        foreach ($recivedAreaData as $hookedObjId => $collections) {
            foreach ($collections as $collectionName => $selectedMedia) {
                if (is_array($selectedMedia) && count($selectedMedia) > 0) {
                    $this->handleFeatureSelectedMedia($hook, $collectionName, $selectedMedia);
                }
            }
        }

        return true;
    }
 
    /**
     * mix selected with previously selected
     * old collection is removed new is created some items are preserved
     * 
     */
    public function handleFeatureSelectedMedia(ProcessHook $hook, $collectionName, $selectedMedia)
    {
        $newMediaCollection = new ArrayCollection();
        foreach ($selectedMedia as $relationId => $relationDataArray) {
            if (!array_key_exists('media', $relationDataArray) || array_key_exists('media', $relationDataArray) && empty($relationDataArray['media'])) {
                continue;
            }
            $managedMediaItem = $this->mediaManager->getManager($relationDataArray['media']);
            if (!$managedMediaItem->exists()) {
                continue;
            }
            
            $isNew = array_key_exists('id', $relationDataArray) && strpos($relationDataArray['id'], 'new_') === false 
                    ? false : true;
 
            if ($isNew) {
                // new item
                $hookedMediaRelation = $this->prepareNewHookedMediaRelationItem($hook, $collectionName, $relationDataArray);
                $hookedMediaRelation->setMedia($managedMediaItem->get());
                $newMediaCollection->add($hookedMediaRelation);
                continue;
            }
            
            if (empty($relationDataArray['id'])) {
                continue;
            }
            
            $collectionElement = $this->hookedMediaCollectionsManager->getCollectionElementById($relationDataArray['id']);
            //media comparision so if you update media item it will 
            if ($collectionElement instanceof HooksRelationsEntity && $collectionElement->getMediaId() == $managedMediaItem->getId()) {
                $this->setHookedMediaRelationItemHooks($hook, $collectionElement);
                $this->setHookedMediaRelationItemExtraData($collectionElement, $relationDataArray);
                $newMediaCollection->add($collectionElement);            
            } 
        }      
        $this->clearHookedMediaCollection($hook, $collectionName);
        $this->saveHookedMediaCollection($newMediaCollection);

        return $this;
    }
    
    /**
     * clear old collection
     */
    public function clearHookedMediaCollection(ProcessHook $hook, $collectionName)
    {
        $oldCollectionManager = $this->hookedMediaCollectionsManager->buildCollection();
        $oldCollectionManager->setHookedModule($hook->getCaller());
        $oldCollectionManager->setHookedAreaId($hook->getAreaId());
        $oldCollectionManager->setHookedObjectId($hook->getId());
        $oldCollectionManager->setFeature($collectionName);
        $oldCollectionManager->load();
        $oldCollectionManager->removeCollection();
        
       return $this;
    }
    
    /**
     * store currently processed collection
     * store will save or update
     */
    public function saveHookedMediaCollection($collection)
    {
        $newCollectionManager = $this->hookedMediaCollectionsManager->setNewCollection($collection);
        $newCollectionManager->storeCollection();
        
       return $this;
    }
    
    /**
     * get new item
     */
    public function prepareNewHookedMediaRelationItem(ProcessHook $hook, $collectionName, $relationDataArray)
    {
        $hookedMediaRelation = new HooksRelationsEntity();
        $hookedMediaRelation->setFeature($collectionName);
        $this->setHookedMediaRelationItemHooks($hook, $hookedMediaRelation);
        $this->setHookedMediaRelationItemExtraData($hookedMediaRelation, $relationDataArray);
        
        return $hookedMediaRelation;
    }
    /**
     * set relation data
     */
    public function setHookedMediaRelationItemExtraData($hookedMediaRelation, $relationDataArray)
    {
        $hookedMediaRelation->setFeatureExtra(array_key_exists('featureExtra', $relationDataArray) ? $relationDataArray['featureExtra'] : []);
        $hookedMediaRelation->setRelationExtra(array_key_exists('relationExtra', $relationDataArray) ? $relationDataArray['relationExtra'] : []);
        $hookedMediaRelation->setMediaExtra(array_key_exists('mediaExtra', $relationDataArray) ? $relationDataArray['mediaExtra'] : []);        
        
       return $this;
    }
    
    /**
     * set hook data
     */
    public function setHookedMediaRelationItemHooks(ProcessHook $hook, $hookedMediaRelation)
    {
        $hookedMediaRelation->setHookedModule($hook->getCaller());
        $hookedMediaRelation->setHookedAreaId($hook->getAreaId());
        $hookedMediaRelation->setHookedObjectId($hook->getId());
        $hookedMediaRelation->setHookedUrlObject($hook->getUrl());
        
       return $this;
    }
    
    /**
     * Display hook for delete.
     *
     * @param DisplayHook $hook the hook
     *
     * @return string
     */
    public function delete(DisplayHook $hook)
    {
        return true;
    }

    /**
     * Validate hook for delete.
     *
     * @param ValidationHook $hook the hook
     *
     * @return void (unused)
     */
    public function validateDelete(ValidationHook $hook)
    {
    }

    /**
     * Process hook for delete.
     *
     * @param ProcessHook $hook the hook
     *
     * @return bool
     */
    public function processDelete(ProcessHook $hook)
    {
//        $config = $this->getHookConfig($hook->getCaller(), $hook->getAreaId());

        return true;
    }
}
