<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright (C) 2017 KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Entity\QueryBuilder;

use Doctrine\ORM\QueryBuilder;

/**
 * MediaRelationsQueryBuilder
 *
 * @author Kaik
 */
class MediaRelationsQueryBuilder extends QueryBuilder
{

    public function filterId($id)
    {
        if (is_array($id)) {
            return $this->andWhere('mo.id IN (:id)')->setParameter('id', $id);
        } elseif ($id !== false) {
            return $this->andWhere('mo.id = :id')->setParameter('id', $id);
        }
    }

    public function filterHookedModule($hookedModule)
    {
        if ($hookedModule !== false) {
            return $this->andWhere('mo.hookedModule = :hookedModule')->setParameter('hookedModule', $hookedModule);
        }
    }

    public function filterHookedAreaId($hookedAreaId)
    {
        if ($hookedAreaId !== false) {
            return $this->andWhere('mo.hookedAreaId = :hookedAreaId')->setParameter('hookedAreaId', $hookedAreaId);
        }
    }

    public function filterHookedObjectId($hookedObjectId)
    {
        if ($hookedObjectId !== false) {
            return $this->andWhere('mo.hookedObjectId = :hookedObjectId')->setParameter('hookedObjectId', $hookedObjectId);
        }
    }
    
    public function filterHookedUrlObject($hookedUrlObject)
    {
        if ($hookedUrlObject !== false) {
            return $this->andWhere('mo.hookedUrlObject = :hookedUrlObject')->setParameter('hookedUrlObject', $hookedUrlObject);
        }
    }

    public function filterFeature($feature)
    {
        if ($feature !== false) {
            return $this->andWhere('mo.feature = :feature')->setParameter('feature', $feature);
        }
    }
    
    public function filterMedia($media)
    {
        if ($media !== false) {
            return $this->andWhere('mo.media = :media')->setParameter('media', $media);
        }
    }

    public function addFilter($field, $filter)
    {
        $fn = 'filter' . ucfirst($field);
        if (method_exists($this, $fn)) {
            $this->$fn($filter);
        }
    }

    public function addFilters($f)
    {
        foreach ($f as $field => $filter) {
            $this->addFilter($field, $filter);
        }
    }

    public function addSearch($s)
    {
        $search = $s['search'];
        $search_field = $s['search_field'];

        if ($search === false || $search_field === false) {
            return;
        }

        switch ($search_field) {
//            case 'author':
//                if (is_numeric($search)) {
//                    return $this->filterAuthor($search);
//                } elseif (is_string($search)) {
//                    $uid = UserUtil::getIdFromName($search);
//                    $uid = $uid !== false ? $uid : 0;
//                    return $this->filterAuthor($uid);
//                }
//                break;
            case 'obj_name':
                return $this->andWhere('mo.obj_name LIKE :search')->setParameter('search', '%' . $search . '%');
        }
    }

    public function sort($sortBy, $sortOrder)
    {
        return $this->orderBy('mo.' . $sortBy, $sortOrder);
    }
}
//
//              id
//              media
//		mediaExtra
//              
//		feature	
//		featureExtra	
//		
//		relationExtra		
//		
//		hookedModule		
//		hookedAreaId	
//		hookedObjectId	
//		hookedUrlObject		
//		
//              status
//              createdAt
//		createdBy		
//              updatedAt	
//		updatedBy
//		deletedAt
//		deletedBy