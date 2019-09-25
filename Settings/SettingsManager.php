<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Settings;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Kaikmedia\GalleryModule\Hooks\HookedModuleObject;
use Kaikmedia\GalleryModule\Hooks\BindingObject;
use Zikula\Bundle\HookBundle\Collector\HookCollector;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\ExtensionsModule\Api\CapabilityApi;
use Zikula\ExtensionsModule\Api\VariableApi;

/**
 * Settings manager
 */
class SettingsManager
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var VariableApi
     */
    private $variableApi;

    /**
     * @var CapabilityApi
     */
    private $capabilityApi;

    /**
     * Construct the manager
     *
     * @param TranslatorInterface $translator
     * @param EntityManager $entityManager
     * @param VariableApi $variableApi
     * @param HookCollector $hookCollector
     * @param CapabilityApi $capabilityApi
     */
    public function __construct(
        TranslatorInterface $translator,
        EntityManager $entityManager,
        VariableApi $variableApi,
        HookCollector $hookCollector,
        CapabilityApi $capabilityApi
    ) {
        $this->name = 'KaikmediaGalleryModule';
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->variableApi = $variableApi;
        $this->hookCollector = $hookCollector;
        $this->capabilityApi = $capabilityApi;
        $this->settings = $this->variableApi->getAll($this->name);
        
//        if (!)) {
            $settings['hooks'] = array_key_exists('hooks', $this->settings) ? $this->settings['hooks'] : ['providers' => [], 'subscribers' => []];
//        }
    }

    public function setSettings($settings)
    {
        $this->settings = $settings;
        
        return true;
    }

    public function getSettings()
    {
        return $this->settings;
    }
    
    public function getUploadDir()
    {
        return $this->get('upload_dir');
    }

    public function getAll()
    {
        return $this->getSettings();
    }

    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->settings) ? $this->settings[$key] : $default;
    }

    public function getSettingsArray()
    {
        $array = $this->settings->toArray();

        return $array;
    }

    public function getSettingsForForm()
    {
        $settings = $this->settings;
        /*
         * Hmm
         *
         * @todo add hooks category check
         */
//        dump($settings);
        $settings['hooks'] = $this->getHooks();
        
        return $settings;
    }

    public function saveSettings()
    {
        $settings = $this->getSettings();
        
        if (array_key_exists('save', $settings)) {
            unset($settings['save']);
        }

        if (array_key_exists('_token', $settings)) {
            unset($settings['_token']);
        }
//        dump($settings);
        return $this->variableApi->setAll($this->name, $settings);
    }

    public function getHooks()
    {
        return ['providers' => $this->getProviders(), 'subscribers' => []];
    }

    public function getProvidersSettings() 
    {
        return array_key_exists('hooks', $this->settings) ? $this->settings['hooks']['providers'] : [];
    }
    
    public function getSettingsForProviderArea($area_name) 
    {       
        return (array_key_exists($this->decodeAreaName($area_name), $this->getProvidersSettings())) ? $this->getProvidersSettings()[$this->decodeAreaName($area_name)] : [];     
    }
    
    public function getProviders()
    {
        $providersCollection = $this->getProvidersCollection();

        return $providersCollection;
    }
    
    public function getProvidersCollection() 
    {
        $providers = $this->hookCollector->getProviderAreasByOwner($this->name);
        $providersCollection = new ArrayCollection(); 
        foreach ($providers as $key => $area_name) {
            //each provider fresh modules/areas/bindings collection and provider settings
            $provider = $this->getProviderForArea($area_name);
            $providersCollection->set($this->decodeAreaName($area_name), $provider);
        }
        
        return $providersCollection;
    }

    public function getProviderForArea($area_name) 
    {
        $provider = $this->hookCollector->getProvider($area_name);
        $modules = $this->getProviderModulesSub();
        $provider->setModules($modules);
        $this->providerUpdateBindingObjects($provider);
        $this->updateBindingsStatusForProvider($provider);
        $this->updateProviderModulesSettings($provider);
        
        return $provider;
    }
    
    public function getProviderModulesSub() 
    {
        $subscriberModules = $this->hookCollector->getOwnersCapableOf(HookCollector::HOOK_SUBSCRIBER);    
        $modules = new ArrayCollection();
        foreach ($subscriberModules as $key => $subscriberModule) {
            $module = new HookedModuleObject($subscriberModule, []);
            // areas
            $areas = $this->hookCollector->getSubscriberAreasByOwner($subscriberModule);
            foreach ($areas as $key => $area) {
                $module->getAreas()->set($this->decodeAreaName($area), $this->getBindingObjectForArea($area)); 
            }

            $modules->set($subscriberModule, $module);
        }

        return $modules;
    }
    
    public function providerUpdateBindingObjects($provider) 
    {
        foreach ($provider->getModules() as $moduleKey => $module) {
            foreach ($module->getAreas() as $key => $bindingObj) {
                $bindingObj->setProvider($provider);
                $bindingObj->setForm($provider->getBindingForm());
            }
        }
    }
    
    public function updateProviderModulesSettings($provider) 
    {
        $providerSavedSettings = $this->getSettingsForProviderArea($provider->getAreaName());
        if (!array_key_exists('modules', $providerSavedSettings)) {
            $providerSavedSettings['modules'] = [];
        }
        
        foreach ($provider->getModules() as $moduleKey => $module) {
            $moduleSettings = array_key_exists($moduleKey, $providerSavedSettings['modules']) ? $providerSavedSettings['modules'][$moduleKey] : [];
            $module->setEnabled(array_key_exists('enabled', $moduleSettings) ? $moduleSettings['enabled'] : $module->getEnabled());
            $this->updateProviderModulesAreasSettings($provider, $module, $moduleSettings);
        }  
    }
    
    public function updateProviderModulesAreasSettings($provider, $module, $moduleSettings) 
    {
        $savedAreasData = array_key_exists('areas', $moduleSettings) ? $moduleSettings['areas'] : [] ;
        foreach ($module->getAreas() as $areaKey => $bindingObj) {
            $areaSettings = array_key_exists($areaKey, $savedAreasData) ? $savedAreasData[$areaKey] : [];
            $bindingObj->setSettings($provider->parseProviderSettingsForArea($areaSettings));
        }
    }
    
    public function updateBindingsStatusForProvider($provider) 
    {
        $bindings = $this->getBindingsForArea($provider->getAreaName());
        foreach ($bindings as $key => $value) {
            $moduleObj = $provider->getModules()->get($value['sowner']);
            $bindingObj = $moduleObj->getAreas()->get($this->decodeAreaName($value['sareaid']));
            $bindingObj->setEnabled(true);       
        }
    }
    
    public function getBindingObjectForArea($area) 
    {
        $bindingObj = new BindingObject();
        $bindingObj->setSubscriber($this->hookCollector->getSubscriber($area));
        $bindingObj->setSubscriberArea($area);
        
        return $bindingObj;
    }
    
    public function decodeAreaName($area) 
    {
        return str_replace('.', '-', $area);
    }
    
    public function getBindingsForArea($area_name) 
    {
        $order = new \Doctrine\ORM\Query\Expr\OrderBy();
        $order->add('t.sortorder', 'ASC');
        $order->add('t.pareaid', 'ASC');

        $bindings = $this->entityManager->createQueryBuilder()->select('t')
                         ->from('Zikula\Bundle\HookBundle\Dispatcher\Storage\Doctrine\Entity\HookBindingEntity', 't')
                         ->where("t.pareaid = ?1")
                         ->orderBy($order)
                         ->getQuery()->setParameter(1, $area_name)
                         ->getArrayResult();
        
        return $bindings;
    }
    
    public function getSubscribers()
    {
        $settings = $this->settings['hooks']['subscribers'];
        $subscribers = $this->hookCollector->getSubscriberAreasByOwner($this->name);
        $providerModules = $this->hookCollector->getOwnersCapableOf(HookCollector::HOOK_PROVIDER);
        $subscribersCollection = new ArrayCollection();
        foreach ($subscribers as $key => $area_name) {
            $subscriber = $this->hookCollector->getSubscriber($area_name);
            $modules = new ArrayCollection();
            foreach ($providerModules as $key => $providerModule) {
                $moduleObj = new HookedModuleObject($providerModule, []);
                $areas = $this->hookCollector->getProviderAreasByOwner($providerModule);
                foreach ($areas as $key => $area) {
                    $bindingObj = new BindingObject();
                    $bindingObj->setSubscriber($subscriber);
                    $bindingObj->setForm($subscriber->getBindingForm());
                    $bindingObj->setProvider($area);
                    $moduleObj->getAreas()->set(str_replace('.', '-', $area), $bindingObj);
                }
                $modules->set($providerModule, $moduleObj);
            }
            $order = new \Doctrine\ORM\Query\Expr\OrderBy();
            $order->add('t.sortorder', 'ASC');
            $order->add('t.sareaid', 'ASC');

            $bindings = $this->entityManager->createQueryBuilder()->select('t')
                             ->from('Zikula\Bundle\HookBundle\Dispatcher\Storage\Doctrine\Entity\HookBindingEntity', 't')
                             ->where("t.sareaid = ?1")
                             ->orderBy($order)
                             ->getQuery()->setParameter(1, $area_name)
                             ->getArrayResult();

            foreach ($bindings as $key => $value) {
                $moduleObj = $modules->get($value['powner']);
                $bindingObj = $moduleObj->getAreas()->get(str_replace('.', '-', $value['pareaid']));
                $bindingObj->setEnabled(true);
                $moduleObj->getAreas()->set(str_replace('.', '-', $value['pareaid']), $bindingObj);
                $modules->set($moduleObj->getName(), $moduleObj);
            }
            $subscriber->setModules($modules);
            $subscribersCollection->set(str_replace('.', '-', $area_name), $subscriber);
        }

        foreach ($subscribersCollection as $key => $subscriber) {
            if (null != $settings && array_key_exists(str_replace('.', '-', $key), $settings)) {
                $subscriberSettings = $settings[str_replace('.', '-', $key)];
                $subscriber->setSettings($subscriberSettings);
                if (array_key_exists('modules', $subscriberSettings)) {
                    foreach ($subscriber->getModules() as $moduleKey => $module) {
                        $moduleSettings = array_key_exists($moduleKey, $subscriberSettings['modules'])
                            ? $subscriberSettings['modules'][$moduleKey]
                            : [];
                        $module->setEnabled(array_key_exists('enabled', $moduleSettings) ? $moduleSettings['enabled'] : $module->getEnabled());
                        if (array_key_exists('areas', $moduleSettings)) {
                            foreach ($module->getAreas() as $areaKey => $area) {
                                $areaSettings = array_key_exists($areaKey, $moduleSettings['areas'])
                                    ? $moduleSettings['areas'][$areaKey]
                                    : [];
                                // @todo solve enabled status ( enabled setting only for actually enabled hook)
                                $area->setEnabled(array_key_exists('enabled', $areaSettings) ? $areaSettings['enabled'] : $areaSettings->getEnabled());
                                $area->setSettings($areaSettings);
                            }
                        }
                    }
                }
            }
        }

        return $subscribersCollection;
    }

    /**
     * get settings for hook
     * generates value if not yet set.
     *
     * @param $hook
     *
     * @return array
     */
    public function getProviderConfigForCaller($provider_area, $caller, $caller_area)
    {   
        $provider = $this->getProviderForArea($provider_area);
        
        if (!$provider) {
            return [];
        }
        
        $caller_module = $provider->getModules()->get($caller);
        $config = $caller_module->getAreas()->get($this->decodeAreaName($caller_area));

        return $config;
    }
}

    //        $provider_config = $this->getSettingsForProviderArea($provider_area);
//        $default = [];
//        if (array_key_exists($caller, $provider_config['modules'])) {
////            $default['display_title'] = $provider_config['modules'][$caller]['enabled'];
//            $default['enabled'] = $provider_config['modules'][$caller]['enabled'];
//        } else {
//            return $default;
//        }
//        
//        if (array_key_exists('areas', $provider_config['modules'][$caller]) && array_key_exists(decodeAreaName($caller_area), $provider_config['modules'][$caller]['areas'])) {
//            $subscribedModuleAreaSettings = $provider_config['modules'][$caller]['areas'][decodeAreaName($caller_area)];
//            $default['enabled'] = array_key_exists('enabled', $subscribedModuleAreaSettings) ? $subscribedModuleAreaSettings['enabled'] : $default['enabled'];
//            $default['settings'] = array_key_exists('settings', $subscribedModuleAreaSettings) ? $subscribedModuleAreaSettings['settings'] : $default['settings'];
//        } else {
//            return $default;
//        }  
        
        
//    /**
//     * get settings for hook
//     * generates value if not yet set.
//     *
//     * @param $hook
//     *
//     * @return array
//     */
//    public function getHookConfig($area)
//    {
////        $default = [
////            'display_title' => '',
////            'modules' => []
////        ];
//        
//        // module settings
////        $settings = $this->get('hooks', false);
// 
//        // this provider config
////        $provider_config = array_key_exists(str_replace('.', '-', $area), $settings['providers']) ? $settings['providers'][str_replace('.', '-', $area)] : null;
//        $provider_config = $this->getSettingsForProviderArea($area);
//        // no configuration for this module return default
//
//        if (null == $provider_config) {
//            return $default;
//        } else {
//            $default['display_title'] = array_key_exists('display_title', $provider_config) ? $provider_config['display_title'] : $default['display_title'];
//            $default['modules'] = array_key_exists('modules', $provider_config) ? $provider_config['modules'] : $default['modules'];
//        }
//
//        return $default;
//    }
    
//
//    public function getAdmins()
//    {
//        $adminsGroup = $this->entityManager->getRepository('Zikula\GroupsModule\Entity\GroupEntity')->find(2);
//        $admins = ['-1' => $this->translator->__('disabled')];
//        foreach ($adminsGroup['users'] as $admin) {
//            $admins[$admin->getUid()] = $admin->getUname();
//        }
//
//        return $admins;
//    }
//    private $name = 'KaikmediaGalleryModule';
//
//    private $displayName = 'KMGallery';
//
//    protected $variablesManager;
//
//    private $settings;
//
//    /**
//     * construct
//     */
//    public function __construct(
//        VariableApi $variablesManager
//    ) {
//        $this->settings = new SettingsCollection();
//        $this->variablesManager = $variablesManager;
////        $dbSettings = $this->variablesManager->getAll($this->name);
//        //dump($dbSettings);
////        if (is_array($dbSettings) && !empty($dbSettings)) {
////            $this->settings->clear();
////            $global = [];// $dbSettings[$this->name];
//////            unset($dbSettings[$this->name]);
////            $this->settings->set($this->name, $global);
////            foreach($dbSettings as $settingObject){
////                $this->settings->set($settingObject->getName(), $settingObject);
////            }
////        } else {
////            //settings init
////            $this->settings->addDefault();
////        }
//    }
//
//    public function setSettings(SettingsCollection $settings)
//    {
//        if(!$settings->containsKey($this->name)) {
//           return false;
//        }
//
//        $this->settings->postSubmit($settings);
//
//        return true;
//    }
//
//    public function getSettings()
//    {
//        return $this->settings;
//    }
//
//    public function getSettingsArray()
//    {
//        $array = $this->settings->toArray();
//
//        return $array;
//    }
//
//    public function getSettingsForForm()
//    {
//        return ['settings' => $this->settings];
//    }
//
//    public function saveSettings()
//    {
//        return $this->variablesManager->setAll($this->name, $this->settings->toArrayForStorage());
//    }