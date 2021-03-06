<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright (C) 2017 KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Kaikmedia\GalleryModule\Entity\MediaQueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class AlbumsRepository extends NestedTreeRepository
{
    /**
     * Tree array for jstree
     *
     * @return tree array
     */
    public function getAlbumJsTree()
    {
//        $em = \ServiceUtil::getService('doctrine.entitymanager');
        $repository = $em->getRepository('Kaikmedia\GalleryModule\Entity\AlbumEntity');
        $albums = $repository->findAll();

        foreach ($albums as $album) {
            $arrayTree[] = $album->getAsJsTreeArray();
        }
        return $arrayTree;
    }
}