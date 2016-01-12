<?php

/**
 * Copyright (c) KaikMedia.com 2015
 */

namespace Kaikmedia\GalleryModule;

use Zikula\Core\AbstractBundle;
use Zikula\Core\ExtensionInstallerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zikula\Component\HookDispatcher\AbstractContainer;
use Zikula\Component\HookDispatcher\SubscriberBundle;

class GalleryModuleInstaller implements ExtensionInstallerInterface, ContainerAwareInterface {

    use TranslatorTrait;
    //use ExtensionVariablesTrait;

    /**
     * @var string the bundle name.
     */
    protected $name;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var AbstractBundle
     */
    protected $bundle;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Zikula\Core\Doctrine\Helper\SchemaHelper
     */
    protected $schemaTool;

    /**
     * @var \Zikula\ExtensionsModule\Api\HookApi
     */
    protected $hookApi;
    private $entities = array(
        'Kaikmedia\GalleryModule\Entity\Media\AbstractMediaEntity',
        'Kaikmedia\GalleryModule\Entity\Media\AbstractUploadableEntity',
        'Kaikmedia\GalleryModule\Entity\Media\ImageEntity',
        'Kaikmedia\GalleryModule\Entity\Media\PdfEntity',
        'Kaikmedia\GalleryModule\Entity\Media\YoutubeEntity',
        'Kaikmedia\GalleryModule\Entity\Media\UrlEntity',
        'Kaikmedia\GalleryModule\Entity\Relations\AbstractRelationsEntity',      
        'Kaikmedia\GalleryModule\Entity\Relations\KaikmediaPagesModuleRelationsEntity',
        'Kaikmedia\GalleryModule\Entity\Relations\ZikulaUsersModuleRelationsEntity'
    );

    public function setBundle(AbstractBundle $bundle) {
        $this->bundle = $bundle;
        $this->name = $bundle->getName();
        if ($this->container) {
            // both here and in `setContainer` so either method can be called first.
            $this->container->get('translator')->setDomain($this->bundle->getTranslationDomain());
        }
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
        $this->setTranslator($container->get('translator'));
        $this->entityManager = $container->get('doctrine.entitymanager');
        $this->schemaTool = $container->get('zikula.doctrine.schema_tool');
        $this->extensionName = $this->name; // for ExtensionVariablesTrait
        $this->variableApi = $container->get('zikula_extensions_module.api.variable'); // for ExtensionVariablesTrait
        $this->hookApi = $container->get('zikula_extensions_module.api.hook');
        if ($this->bundle) {
            $container->get('translator')->setDomain($this->bundle->getTranslationDomain());
        }
    }

    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    /**
     * Convenience shortcut to add a session flash message.
     * @param $type
     * @param $message
     */
    public function addFlash($type, $message) {
        if (!$this->container->has('session')) {
            throw new \LogicException('You can not use the addFlash method if sessions are disabled.');
        }

        $this->container->get('session')->getFlashBag()->add($type, $message);
    }

    public function install() {

        // create table
        
        \DoctrineHelper::createSchema($this->entityManager, $this->entities);
        
        //$this->variableApi->setAll($this->name, $modvars);
        /*
        try {
            $this->schemaTool->create($this->entities);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return false;
        }
        
        // insert default category
        try {
            $this->createCategoryTree();
        } catch (\Exception $e) {
            $this->addFlash('error', $this->__f('Did not create default categories (%s).', $e->getMessage()));
        }
        // set up config variables
        $modvars = array(
            'itemsperpage' => 25,
            'enablecategorization' => true
        );
        $this->variableApi->setAll($this->name, $modvars);
        $hookContainer = $this->hookApi->getHookContainerInstance($this->bundle->getMetaData());
        \HookUtil::registerSubscriberBundles($hookContainer->getHookSubscriberBundles());
        \HookUtil::registerProviderBundles($hookContainer->getHookProviderBundles());
        */
        // initialisation successful
        return true;
    }

    public function upgrade($oldversion) {

        return true;
    }

    public function uninstall() {
        // drop table
        //$this->schemaTool->drop($this->entities);
        // Delete any module variables
        //$this->variableApi->delAll($this->name);
        // Delete entries from category registry
        //\CategoryRegistryUtil::deleteEntry($this->bundle->getName());
        //$hookContainer = $this->hookApi->getHookContainerInstance($this->bundle->getMetaData());
        //\HookUtil::unregisterSubscriberBundles($hookContainer->getHookSubscriberBundles());
        //\HookUtil::unregisterProviderBundles($hookContainer->getHookProviderBundles());
        // Deletion successful
        return true;
    }

    /**
     * create the category tree
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If Root category not found.
     * @throws \Exception
     *
     * @return boolean
     */
    private function createCategoryTree() {
        // create category
        \CategoryUtil::createCategory('/__SYSTEM__/Modules', $this->bundle->getName(), null, $this->__('Gallery'), $this->__('Gallery categories'));
        // create subcategory
        \CategoryUtil::createCategory('/__SYSTEM__/Modules/KaikmediaGalleryModule', 'Category1', null, $this->__('Category 1'), $this->__('Initial sub-category created on install'), array('color' => '#99ccff'));
        \CategoryUtil::createCategory('/__SYSTEM__/Modules/KaikmediaGalleryModule', 'Category2', null, $this->__('Category 2'), $this->__('Initial sub-category created on install'), array('color' => '#cceecc'));
        // get the category path to insert Pages categories
        $rootcat = \CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/KaikmediaGalleryModule');
        if ($rootcat) {
            // create an entry in the categories registry to the Main property
            if (!\CategoryRegistryUtil::insertEntry($this->bundle->getName(), 'AlbumEntity', 'Main', $rootcat['id'])) {
                throw new \Exception('Cannot insert Category Registry entry.');
            }
        } else {
            throw new NotFoundHttpException('Root category not found.');
        }
        return true;
    }
}
