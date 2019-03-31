<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule;

use Kaikmedia\GalleryModule\Entity\Media\AbstractMediaEntity;
use Kaikmedia\GalleryModule\Entity\Media\AbstractUploadableEntity;
use Kaikmedia\GalleryModule\Entity\Media\ImageEntity;
use Kaikmedia\GalleryModule\Entity\Media\PdfEntity;
use Kaikmedia\GalleryModule\Entity\Media\YoutubeEntity;
use Kaikmedia\GalleryModule\Entity\Media\UrlEntity;
use Kaikmedia\GalleryModule\Entity\Relations\HooksRelationsEntity;
use Zikula\CategoriesModule\Entity\CategoryAttributeEntity;
use Zikula\CategoriesModule\Entity\CategoryEntity;
use Zikula\CategoriesModule\Entity\CategoryRegistryEntity;
use Zikula\Core\AbstractExtensionInstaller;

class GalleryModuleInstaller extends AbstractExtensionInstaller
{
    private $entities = [
        AbstractMediaEntity::class,
        AbstractUploadableEntity::class,
        ImageEntity::class,
        PdfEntity::class,
        YoutubeEntity::class,
        UrlEntity::class,
        HooksRelationsEntity::class,
    ];

    public function install()
    {
        // create table
        try {
            $this->schemaTool->create($this->entities);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return false;
        }
        // insert default category
//        try {
//            $this->createCategoryTree();
//        } catch (\Exception $e) {
//            $this->addFlash('error', $this->__f('Did not create default categories (%s).', ['%s' => $e->getMessage()]));
//        }
        // set up config variables
        $modvars = [
            'itemsperpage' => 25,
            'enablecategorization' => true,
            'upload_dir' => '/web/uploads',
            'hooks' => ['providers' => [],
                        'subscribers'=> []
                ]
        ];
        $this->setVars($modvars);
        // initialisation successful

        return true;
    }

    public function upgrade($oldversion)
    {
        return true;
    }

    public function uninstall()
    {
        // drop table
        $this->schemaTool->drop($this->entities);
        // Delete any module variables
        $this->delVars();
        // Delete entries from category registry
//        $registries = $this->container->get('zikula_categories_module.category_registry_repository')->findBy(['modname' => $this->bundle->getName()]);
//        foreach ($registries as $registry) {
//            $this->entityManager->remove($registry);
//        }
//        $this->entityManager->flush();
        // Deletion successful

        return true;
    }

    /**
     * create the category tree
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If Root category not found
     * @throws \Exception
     *
     * @return boolean
     */
    private function createCategoryTree()
    {
        $locale = $this->container->get('request_stack')->getCurrentRequest()->getLocale();
        $repo = $this->container->get('zikula_categories_module.category_repository');
        // create pages root category
        $parent = $repo->findOneBy(['name' => 'Modules']);
        $pagesRoot = new CategoryEntity();
        $pagesRoot->setParent($parent);
        $pagesRoot->setName($this->bundle->getName());
        $pagesRoot->setDisplay_name([
            $locale => $this->__('Gallery', 'kaikmediagallerymodule', $locale)
        ]);
        $pagesRoot->setDisplay_desc([
            $locale => $this->__('Gallery and media', 'kaikmediagallerymodule', $locale)
        ]);
        $this->entityManager->persist($pagesRoot);
        // create children
        $category1 = new CategoryEntity();
        $category1->setParent($pagesRoot);
        $category1->setName('Category1');
        $category1->setDisplay_name([
            $locale => $this->__('Category 1', 'kaikmediagallerymodule', $locale)
        ]);
        $category1->setDisplay_desc([
            $locale => $this->__('Initial sub-category created on install', 'kaikmediagallerymodule', $locale)
        ]);
        $attribute = new CategoryAttributeEntity();
        $attribute->setAttribute('color', '#99ccff');
        $category1->addAttribute($attribute);
        $this->entityManager->persist($category1);
        $category2 = new CategoryEntity();
        $category2->setParent($pagesRoot);
        $category2->setName('Category2');
        $category2->setDisplay_name([
            $locale => $this->__('Category 2', 'kaikmediagallerymodule', $locale)
        ]);
        $category2->setDisplay_desc([
            $locale => $this->__('Initial sub-category created on install', 'kaikmediagallerymodulee', $locale)
        ]);
        $attribute = new CategoryAttributeEntity();
        $attribute->setAttribute('color', '#cceecc');
        $category2->addAttribute($attribute);
        $this->entityManager->persist($category2);
        // create Registry
        $registry = new CategoryRegistryEntity();
        $registry->setCategory($pagesRoot);
        $registry->setEntityname('AlbumEntity');
        $registry->setModname($this->bundle->getName());
        $registry->setProperty('Main');
        $this->entityManager->persist($registry);
        $this->entityManager->flush();

        return true;
    }
}
