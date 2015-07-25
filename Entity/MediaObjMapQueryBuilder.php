<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Entity;

use UserUtil;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of MediaQueryBuilder
 * 
 * @author Kaik
 */
class MediaObjMapQueryBuilder extends QueryBuilder
{

    public function filterId($id)
    {
        if (is_array($id)) {
            return $this->andWhere('mo.id IN (:id)')->setParameter('id', $id);
        } elseif ($id !== false) {
            return $this->andWhere('mo.id = :id')->setParameter('id', $id);
        }
    }

    public function filterObj_name($obj_name)
    {
        if ($obj_name !== false) {
            return $this->andWhere('mo.name = :obj_name')->setParameter('obj_name', $obj_name);
        }
    }

    public function filterAuthor($author)
    {
        if ($author !== false) {
            return $this->andWhere('mo.author = :author')->setParameter('author', $author);
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
            case 'author':
                if (is_numeric($search)) {
                    return $this->filterAuthor($search);
                } elseif (is_string($search)) {
                    $uid = UserUtil::getIdFromName($search);
                    $uid = $uid !== false ? $uid : 0;
                    return $this->filterAuthor($uid);
                }
                break;
            case 'obj_name':
                return $this->andWhere('mo.obj_name LIKE :search')->setParameter('search', '%' . $search . '%');
        }
    }

    public function sort($sortBy, $sortOrder)
    {
        return $this->orderBy('mo.' . $sortBy, $sortOrder);
    }
}
