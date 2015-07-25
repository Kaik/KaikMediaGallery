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
        'Kaikmedia\GalleryModule\Entity\MediaObjMapEntity',
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
        $this->setVar('upload_dir', 'kmgallery');
        $this->setVar('upload_max_media_size', 0);
        $this->setVar('upload_max_total_size', 0);
        $this->setVar('upload_allowed_ext', '');
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