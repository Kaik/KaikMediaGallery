<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule;

use HookUtil;
use Exception;
use DoctrineHelper;

class GalleryModuleInstaller extends \Zikula_AbstractInstaller
{

    private $_entities = array(
        'Kaikmedia\GalleryModule\Entity\MappingEntity',
        'Kaikmedia\GalleryModule\Entity\MediaEntity'
    );

    public function install()
    {
        try {
            DoctrineHelper::createSchema($this->entityManager, $this->_entities);
        } catch (\Exception $e) {
            $this->request->getSession()
                ->getFlashBag()
                ->add('error', $e->getMessage());
            return false;
        }
        
        $this->setVar('itemsperpage', 10);        
        return true;
    }

    public function upgrade($oldversion)
    {
        // first version nothing to upgrade from;
        return true;
    }

    public function uninstall()
    {
        try {
            DoctrineHelper::dropSchema($this->entityManager, $this->_entities);
        } catch (Exception $e) {
            $this->request->getSession()
                ->getFlashBag()
                ->add('error', $e->getMessage());
            return false;
        }
        // remove module vars
        $this->delVars();
        // unregister hooks - unused for initial state
        //HookUtil::unregisterSubscriberBundles($this->version->getHookSubscriberBundles());
        //HookUtil::unregisterProviderBundles($this->version->getHookProviderBundles());
        // Deletion successful
        return true;
    }
}