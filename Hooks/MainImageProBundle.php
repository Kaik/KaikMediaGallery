<?php

/**
 * Dizkus
 *
 * @copyright (c) 2001-now, Dizkus Development Team
 *
 * @see https://github.com/zikula-modules/Dizkus
 *
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

namespace Kaikmedia\GalleryModule\Hooks;

use Doctrine\ORM\EntityManager;
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
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\PermissionsModule\Api\PermissionApi;
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
     * @var EngineInterface
     */
    protected $renderEngine;

    /**
     * @var VariableApi
     */
    private $variableApi;

    /**
     * @var PermissionApi
     */
    private $permissionApi;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var area
     */
    private $area = 'provider.gallery.ui_hooks.main_image';

    /**
     * Construct the manager
     *
     * @param ZikulaHttpKernelInterface $kernel
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     * @param RequestStack $requestStack
     * @param EngineInterface $renderEngine
     * @param VariableApi $variableApi
     * @param PermissionApi $permissionApi
     * @param EntityManager $entityManager
     */
    public function __construct(
        ZikulaHttpKernelInterface $kernel,
        TranslatorInterface $translator,
        RouterInterface $router,
        EngineInterface $renderEngine,
        VariableApi $variableApi,
        PermissionApi $permissionApi,
        EntityManager $entityManager
    ) {
        $this->kernel = $kernel;
        $this->translator = $translator;
        $this->router = $router;
        $this->renderEngine = $renderEngine;
        $this->variableApi = $variableApi;
        $this->permissionApi = $permissionApi;
        $this->entityManager = $entityManager;
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
        return 'Kaikmedia\\GalleryModule\\Form\\Type\\Hook\\MainMediaProviderSettingsType';
    }

    public function getBindingForm()
    {
        return 'Kaikmedia\\GalleryModule\\Form\\Type\\Hook\\MainMediaProviderBindingType';
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
        // first check if the user is allowed to do any comments for this module/objectid
        if (!$this->permissionApi->hasPermission("{$hook->getCaller()}", '::', ACCESS_READ)) {
            return;
        }

        $config = $this->getHookConfig($hook->getCaller(), $hook->getAreaId());


        $feature = array_key_exists('feature', $config['display']) ? $config['display']['feature'] : false;
        $mode = array_key_exists('mode', $config['display']) ? $config['display']['mode'] : 0;
        $first = array_key_exists('first', $config['display']) ? $config['display']['first'] : false;
        $width = array_key_exists('width', $config['display']) ? $config['display']['width'] : false;
        $height = array_key_exists('feature', $config['display']) ? $config['display']['height'] : false;

        // sinle element

        $hookedMedia = $this->getHookedMedia($hook->getCaller(), $hook->getId());
        if (!$hookedMedia) {

            $content = '';
            goto display;
        }
        
        $featuredMedia = null;
        foreach ($hookedMedia as $item) {
            if ($item->getfeature() == $feature) {
                $featuredMedia = $item;
                break;
            }
        }
        
        $featuredMedia = (!$featuredMedia && count($hookedMedia) > 0) ? reset($hookedMedia) : $featuredMedia;
        if (!$featuredMedia instanceof HooksRelationsEntity) {
            $content = '';
            goto display;   
        }    
            
        $mediaExtra = $featuredMedia->getMedia()->getMediaExtra();

        $filename = array_key_exists('fileName', $mediaExtra) ? $mediaExtra['fileName'] : false ;
        if (!$filename) {

            $content = '';
            goto display;
        }

        $uploadRoot = $this->variableApi->get($this->getOwner(), 'uploacd_dir', '/uploads');
        $subdir = array_key_exists('subdir', $mediaExtra) ? $mediaExtra['subdir'] . '/' : '';
        $content = $uploadRoot . '/' . $subdir . $filename;

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
        $content = '<p> test</p>';
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
     * This function creates topic only under specific circumstances
     * - forum is set and exists
     * - topic mode is either 0 or 1 (2 is automatic on first post)
     * - createTopic is set to true
     *
     * When topic does not exists and comments are enabled nothing will be shown unless
     * mode is set to 2 in this case first comment form will be shown
     *
     * When mode is set to 0 or 1 either admin or item creator/owner/editor is able to create topic and enable comments on this item
     *
     * @param ProcessHook $hook the hook
     *
     * @return bool
     */
    public function processEdit(ProcessHook $hook)
    {
        return true;
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
        $config = $this->getHookConfig($hook->getCaller(), $hook->getAreaId());

        return true;
    }

    /**
     * get settings for hook
     * generates value if not yet set.
     *
     * @param $hook
     *
     * @return array
     */
    public function getHookConfig($module, $areaid = null)
    {
        $default = [
            'upload' => null,
            'display' => null,
        ];
        // module settings
        $settings = $this->variableApi->get($this->getOwner(), 'hooks', false);
        // this provider config
        $config = array_key_exists(str_replace('.', '-', $this->area), $settings['providers']) ? $settings['providers'][str_replace('.', '-', $this->area)] : null;
        // no configuration for this module return default

        if (null == $config) {
            return $default;
        } else {
            $default['upload'] = array_key_exists('upload', $config) ? $config['upload'] : $default['upload'];
            $default['display'] = array_key_exists('display', $config) ? $config['display'] : $default['display'];
        }

        // module provider area module area settings
        if (array_key_exists($module, $config['modules']) && array_key_exists('areas', $config['modules'][$module]) && array_key_exists(str_replace('.', '-', $areaid), $config['modules'][$module]['areas'])) {
            $subscribedModuleAreaSettings = $config['modules'][$module]['areas'][str_replace('.', '-', $areaid)];
            if (array_key_exists('settings', $subscribedModuleAreaSettings)) {
                $default['upload'] = array_key_exists('upload', $subscribedModuleAreaSettings['settings']) ? $subscribedModuleAreaSettings['settings']['upload'] : $default['upload'];
                $default['display'] = array_key_exists('display', $subscribedModuleAreaSettings['settings']) ? $subscribedModuleAreaSettings['settings']['display'] : $default['display'];
            }
        }

        return $default;
    }

    /**
     * Process hook for delete.
     *
     * @param ProcessHook $hook the hook
     *
     * @return bool
     */
    public function getFeatureMedia($feature = null, $module = null, $objId = null, $area = null)
    {
        if (!$module || !$objId || !$feature) {
            return [];
        } else {
            $filter = ['hookedModule' => $module, 'hookedObjectId' => $objId];

            if ($feature) {
                $filter['feature'] = $feature;
            }

            $media = $this->entityManager
                ->getRepository('Kaikmedia\GalleryModule\Entity\Relations\HooksRelationsEntity')
                    ->findOneBy($filter);
        }

        return $media;
    }

    /**
     * Process hook for delete.
     *
     * @param ProcessHook $hook the hook
     *
     * @return bool
     */
    public function getHookedMedia($module = null, $objId = null, $area = null)
    {
        if (!$module || !$objId) {
            return [];
        } else {
            $filter = ['hookedModule' => $module, 'hookedObjectId' => $objId];

            $media = $this->entityManager
                ->getRepository('Kaikmedia\GalleryModule\Entity\Relations\HooksRelationsEntity')
                    ->findBy($filter);
        }

        return $media;
    }
}
//        dump($content);
//        $content = '';



//-hookedModule: "KaikmediaNewsModule"
//  -hookedAreaId: 0
//  -hookedObjectId: 2051
//  -hookedUrlObject: null
//  -media: ImageEntity {#3743 ▼
//    #path: null
//    #name: null
//    #mimeType: null
//    #size: null
//    #ext: null
//    -file: null
//    -temp: null
//    -legal: "unknow"
//    -publicdomain: false
//    -mediaExtra: array:4 [▼
//      "fileName" => "news5bef2e85ed9cc.jpg"
//      "prefix" => "news"
//      "subdir" => "news"
//      "ext" => "jpg"
//    ]
//    #relations: PersistentCollection {#3744 ▶}
//    -title: "Official_portrait_of_Dominic_Raab_crop_2.jpg"
//    -urltitle: "-934"
//    -description: ""
//    -online: false
//    -depot: false
//    -inmenu: true
//    -inlist: true
//    -language: "all"
//    -layout: "default"
//    -views: 0
//    -author: null
//    -publishedAt: null
//    -expiredAt: null
//    #id: 959
//    -status: "A"
//    -createdAt: DateTime @1542401669 {#3739 ▶}
//    -createdBy: UserEntity {#2896 ▶}
//    -updatedAt: DateTime @1542401669 {#3740 ▶}
//    -updatedBy: UserEntity {#2896 ▶ …2}
//    -deletedBy: null
//    -deletedAt: null
//    #reflection: null
//  }
//  -mediaExtra: []
//  -feature: "featured"
//  -featureExtra: []
//  -relationExtra: array:2 [▼
//    "title" => ""
//    "legal" => ""
//  ]
//  #id: 931
//  -status: "A"
//  -createdAt: DateTime @1542401674 {#3384 ▶}
//  -createdBy: UserEntity {#2896 ▶ …2}
//  -updatedAt: DateTime @1542401674 {#3385 ▶}
//  -updatedBy: UserEntity {#2896 ▶ …2}
//  -deletedBy: null
//  -deletedAt: null
//  #reflection: null



//        // first check if the user is allowed to do any comments for this module/objectid
//        if (!$this->permissionApi->hasPermission("{$hook->getCaller()}", '::', ACCESS_READ)) {
//            return;
//        }
// "display" => array:7 [▼
//    "feature" => "featured"
//    "css" => "featured-media"
//    "width" => "720"
//    "height" => "405"
//    "mode" => "1"
//    "show_title" => "0"
//    "show_legal" => "0"
//        $config = $this->getHookConfig($hook->getCaller(), $hook->getAreaId());
//        $content = [];
//        if (array_key_exists('features', $config) && is_array($config['features'])) {
//            foreach ($config['features'] as $feature) {
//                $feature['relation'] = $this->getFeatureMedia($feature['name'], $hook->getCaller(), $hook->getId());
//                $content[] = $feature;
//            }
//        }
//
//        $hook->setResponse(new DisplayHookResponse('provider.gallery.ui_hooks.media', $content));

//{#{% set gallery=(attribute(hooks, 'provider.gallery.ui_hooks.media') is defined ? attribute(hooks, 'provider.gallery.ui_hooks.media').__toString() : {}) %}
//{% for feature in gallery %}
//        {% if feature.relation.media is defined
//           and feature.relation.media.mediaExtra.fileName is defined
//           and feature.relation.media.mediaExtra.fileName is not empty
//           and feature.name == 'featured'
//        %}
//        {% set main_image=('/uploads/' ~ (feature.relation.media.mediaExtra.subdir is defined ? feature.relation.media.mediaExtra.subdir ~ '/' : '')
//                                  ~ feature.relation.media.mediaExtra.fileName) %}
//    {% endif %}
//{% endfor %}#}

//        $feature['relation'] = $this->getFeatureMedia('featured', $hook->getCaller(), $hook->getId());