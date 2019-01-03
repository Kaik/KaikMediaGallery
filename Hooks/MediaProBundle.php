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
use Kaikmedia\GalleryModule\Entity\Media\AbstractMediaEntity;
use Kaikmedia\GalleryModule\Entity\Relations\HooksRelationsEntity;
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
//use Zikula\DizkusModule\Manager\ForumUserManager;
//use Zikula\DizkusModule\Manager\ForumManager;
//use Zikula\DizkusModule\Manager\PostManager;
//use Zikula\DizkusModule\Manager\TopicManager;
//use Zikula\DizkusModule\HookedTopicMeta\AbstractHookedTopicMeta;
//use Zikula\DizkusModule\HookedTopicMeta\Generic;
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\PermissionsModule\Api\PermissionApi;

/**
 * TopicProBundle
 *
 * @author Kaik
 */
class MediaProBundle extends AbstractProBundle implements HookProviderInterface
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
    private $area = 'provider.gallery.ui_hooks.media';

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
        RequestStack $requestStack,
        EngineInterface $renderEngine,
        VariableApi $variableApi,
        PermissionApi $permissionApi,
        EntityManager $entityManager
    ) {
        $this->kernel = $kernel;
        $this->translator = $translator;
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getMasterRequest();
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
        return $this->translator->__('Media Provider');
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
        return 'Kaikmedia\\GalleryModule\\Form\\Type\\Hook\\MediaProviderSettingsType';
    }

    public function getBindingForm()
    {
        return 'Kaikmedia\\GalleryModule\\Form\\Type\\Hook\\MediaProviderBindingType';
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
        $content = [];
        if (array_key_exists('features', $config) && is_array($config['features'])) {
            foreach ($config['features'] as $feature) {
                $feature['relation'] = $this->getFeatureMedia($feature['name'], $hook->getCaller(), $hook->getId());
                $content[] = $feature;
            }
        }

        $hook->setResponse(new DisplayHookResponse('provider.gallery.ui_hooks.media', $content));
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
        // first check if the user is allowed to do any comments for this module/objectid
        if (!$this->permissionApi->hasPermission("{$hook->getCaller()}", '::', ACCESS_READ)) {
            return;
        }
        // because on publication validation error things come back here
        $postBackrelations = $this->request->request->get('kmgallery_features');
        $config = $this->getHookConfig($hook->getCaller(), $hook->getAreaId());
        if (array_key_exists('features', $config) && is_array($config['features'])) {
            foreach ($config['features'] as $key => $feature) {
                $config['features'][$key]['relation'] = $this->getFeatureMedia($feature['name'], $hook->getCaller(), $hook->getId());
                // case 0 postback does not exist no error - nothing to do
                if (!$postBackrelations) {
                    continue; //case o normal work
                }

                $keyExists = array_key_exists($key, $postBackrelations);
                $postBackMediaExists = $postBackrelations[$key]['media'] !== '' ?: false;
                $postBackRelationExists = ($postBackrelations[$key]['relation'] !== '' && $postBackrelations[$key]['relation'] !== "0") ?: false;
                $relationExists = $config['features'][$key]['relation'] instanceof HooksRelationsEntity ?: false;

                // case 0 postback media does not exist - nothing to do
                if ($keyExists && !$postBackMediaExists && !$postBackRelationExists) {
                    continue;
                }

                // case 2 postback media exists , relation does not - dummy relation with media item will save next time
                if ($keyExists && $postBackMediaExists && !$postBackRelationExists) {
                    //load media
                    $mediaItem = $this->entityManager->getRepository('Kaikmedia\GalleryModule\Entity\Media\AbstractMediaEntity')->find($postBackrelations[$key]['media']);
                    if (!$mediaItem) {
                        continue;
                    }
                    // temp relation will be saved later
                    $tempRelation = new HooksRelationsEntity();
                    $tempRelation->setMedia($mediaItem);
                    $tempRelation->setRelationExtra($postBackrelations[$key]['relationExtra']);
                    $config['features'][$key]['relation'] = $tempRelation;

                    continue;
                }

                // case 3 postback media exists, relation exist too only thing that might have changed is relation data
                if ($relationExists && $keyExists && $postBackMediaExists && $postBackRelationExists) {
                    // just update relation data in case user changed it
                    $config['features'][$key]['relation']->setRelationExtra($postBackrelations[$key]['relationExtra']);
                    continue;
                }
            }
        }

        $content =  $this->renderEngine->render('KaikmediaGalleryModule:Hook:manager.html.twig', [
                'config' => $config
        ]);
        $hook->setResponse(new DisplayHookResponse('provider.gallery.ui_hooks.media', $content));
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
        // post data new settings
        $relations = $this->request->request->get('kmgallery_features');

        $config = $this->getHookConfig($hook->getCaller(), $hook->getAreaId());
        //1. iterate over features
        if (array_key_exists('features', $config) && is_array($config['features'])) {
            foreach ($config['features'] as $key => $feature) {
                $config['features'][$key]['relation'] = $this->getFeatureMedia($feature['name'], $hook->getCaller(), $hook->getId());
                // null or relation
                $toStoreRelation = new HooksRelationsEntity();
                $mediaItem = null;

                $postRelationMediaExist = $relations[$key]['media'] !== '' ?: false;
                $relationExists = $config['features'][$key]['relation'] instanceof HooksRelationsEntity ?: false;

                if ($postRelationMediaExist) {
                    // get media item if null continue maybe with error
                    $mediaItem = $this->entityManager->getRepository('Kaikmedia\GalleryModule\Entity\Media\AbstractMediaEntity')->findOneBy(['id' => $relations[$key]['media']]);
                }

                // case 1 ralation is null and no new media data is recived - nothing to do
                if (!$relationExists && !$postRelationMediaExist) {
                    continue;
                }

                // case 2 relation is null and new media data recived - create relation
                if (!$relationExists && $postRelationMediaExist) {
                    if (!$mediaItem) {
                        continue;
                    }

                    $toStoreRelation->setFeature($feature['name']);
                    $toStoreRelation->setMedia($mediaItem);
                    $toStoreRelation->setHookedModule($hook->getCaller());
//                    $toStoreRelation->setHookedAreaId($hook->getAreaId());
                    $toStoreRelation->setHookedObjectId($hook->getId());
                    $toStoreRelation->setRelationExtra($relations[$key]['relationExtra']);

                    $this->entityManager->persist($toStoreRelation);
                    $this->entityManager->flush();
                    //done
                    continue;
                }

                // case 3 relation is not null and media is equal to relation media - update relation data (title etc)
                if ($relationExists
                    && $postRelationMediaExist
                    && $config['features'][$key]['relation']->getMedia() instanceof AbstractMediaEntity
                    && $config['features'][$key]['relation']->getMedia()->getId() == $relations[$key]['media']
                ) {
                    $config['features'][$key]['relation']->setRelationExtra($relations[$key]['relationExtra']);
                    $this->entityManager->persist($config['features'][$key]['relation']);
                    $this->entityManager->flush();
                    //done
                    continue;
                }

            // loop end
            }
        }

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
//        $config = $this->getHookConfig($hook->getCaller(), $hook->getAreaId());

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
            'features' => null,
        ];
        // module settings
        $settings = $this->variableApi->get($this->getOwner(), 'hooks', false);
        // this provider config
        $config = array_key_exists(str_replace('.', '-', $this->area), $settings['providers']) ? $settings['providers'][str_replace('.', '-', $this->area)] : null;
        // no configuration for this module return default
        if (null == $config) {
            return $default;
        } else {
            $default['features'] = array_key_exists('features', $config) ? $config['features'] : $default['features'];
        }
        // module provider area module area settings
        if (array_key_exists($module, $config['modules']) && array_key_exists('areas', $config['modules'][$module]) && array_key_exists(str_replace('.', '-', $areaid), $config['modules'][$module]['areas'])) {
            $subscribedModuleAreaSettings = $config['modules'][$module]['areas'][str_replace('.', '-', $areaid)];
            if (array_key_exists('settings', $subscribedModuleAreaSettings)) {
                $default['features'] = array_key_exists('features', $subscribedModuleAreaSettings['settings']) ? $subscribedModuleAreaSettings['settings']['features'] : $default['features'];
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
            $media = $this->entityManager
                ->getRepository('Kaikmedia\GalleryModule\Entity\Relations\HooksRelationsEntity')
                    ->findOneBy(['feature' => $feature, 'hookedModule' => $module, 'hookedObjectId' => $objId]);
        }

        return $media;
    }
}
