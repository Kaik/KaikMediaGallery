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
    }

    public function setSettings($settings)
    {
        if (array_key_exists('save', $settings)) {
            unset($settings['save']);
        }

        if (array_key_exists('_token', $settings)) {
            unset($settings['_token']);
        }

        $this->settings = $settings;

        return true;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getAll()
    {
        return $this->getSettings();
    }

    public function get($key, $default = null)
    {
//        return $this->variableApi->get($this->name, $key, $default);
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
        $settings['hooks'] = $this->getHooks();

        return $settings;
    }

    public function saveSettings()
    {
        return $this->variableApi->setAll($this->name, $this->settings);
    }

    public function getHooks()
    {
        //$this->getSubscribers()
        return ['providers' => $this->getProviders(), 'subscribers' => []];
    }

    public function getProviders()
    {
        $settings = $this->settings['hooks']['providers'];
        $providers = $this->hookCollector->getProviderAreasByOwner($this->name);
        $subscriberModules = $this->hookCollector->getOwnersCapableOf(HookCollector::HOOK_SUBSCRIBER);
        $providersCollection = new ArrayCollection();
        foreach ($providers as $key => $area_name) {
            $provider = $this->hookCollector->getProvider($area_name);
            //each provider fresh modules/areas/bindings collection
            $modules = new ArrayCollection();
            foreach ($subscriberModules as $key => $subscriberModule) {
                $moduleObj = new HookedModuleObject($subscriberModule, []);
                $areas = $this->hookCollector->getSubscriberAreasByOwner($subscriberModule);
                foreach ($areas as $key => $area) {
                    $bindingObj = new BindingObject();
                    $bindingObj->setSubscriber($area);
                    $bindingObj->setProvider($provider);
                    $bindingObj->setForm($provider->getBindingForm());
                    $moduleObj->getAreas()->set(str_replace('.', '-', $area), $bindingObj);
                }
                $modules->set($subscriberModule, $moduleObj);
            }

            $order = new \Doctrine\ORM\Query\Expr\OrderBy();
            $order->add('t.sortorder', 'ASC');
            $order->add('t.pareaid', 'ASC');

            $bindings = $this->entityManager->createQueryBuilder()->select('t')
                             ->from('Zikula\Bundle\HookBundle\Dispatcher\Storage\Doctrine\Entity\HookBindingEntity', 't')
                             ->where("t.pareaid = ?1")
                             ->orderBy($order)
                             ->getQuery()->setParameter(1, $area_name)
                             ->getArrayResult();

            foreach ($bindings as $key => $value) {
                $moduleObj = $modules->get($value['sowner']);
                $bindingObj = $moduleObj->getAreas()->get(str_replace('.', '-', $value['sareaid']));
                $bindingObj->setEnabled(true);
                $moduleObj->getAreas()->set(str_replace('.', '-', $value['sareaid']), $bindingObj);
                $modules->set($moduleObj->getName(), $moduleObj);
            }

            $provider->setModules($modules);
            $providersCollection->set(str_replace('.', '-', $area_name), $provider);
        }

         foreach ($providersCollection as $key => $provider) {
            if (null != $settings && array_key_exists(str_replace('.', '-', $key), $settings)) {
                $providerSettings = $settings[str_replace('.', '-', $key)];
                $provider->setSettings($providerSettings);
                if (array_key_exists('modules', $providerSettings)) {
                    foreach ($provider->getModules() as $moduleKey => $module) {
                        $moduleSettings = array_key_exists($moduleKey, $providerSettings['modules'])
                            ? $providerSettings['modules'][$moduleKey]
                            : [];
                        $module->setEnabled(array_key_exists('enabled', $moduleSettings) ? $moduleSettings['enabled'] : $module->getEnabled());
                        if (array_key_exists('areas', $moduleSettings)) {
                            foreach ($module->getAreas() as $areaKey => $area) {
                                $areaSettings = array_key_exists($areaKey, $moduleSettings['areas'])
                                    ? $moduleSettings['areas'][$areaKey]
                                    : [];
                                // @todo solve enabled status ( enabled setting only for actually enabled hook)
                                $areaEnabled = is_array($areaSettings) ? : $areaSettings->getEnabled();
                                $area->setEnabled(array_key_exists('enabled', $areaSettings) ? $areaSettings['enabled'] : $areaEnabled);
                                $area->setSettings($areaSettings);
                            }
                        }
                    }
                }
            }
        }

        return $providersCollection;
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
}


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