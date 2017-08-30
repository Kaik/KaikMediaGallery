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
 * Description of MediaQueryBuilder
 *
 * @author Kaik
 */
class MediaQueryBuilder extends QueryBuilder
{

    public function filterId($id)
    {
        if (is_array($id)) {
            return $this->andWhere('m.id IN (:id)')->setParameter('id', $id);
        } elseif ($id !== false) {
            return $this->andWhere('m.id = :id')->setParameter('id', $id);
        }
    }

    public function filterName($name)
    {
        if ($name !== false) {
            return $this->andWhere('m.name = :name')->setParameter('name', $name);
        }
    }

    public function filterPublicdomain($publicdomain)
    {
        if ($publicdomain === false) {

        }

        if ($publicdomain === true) {
            return $this->andWhere('m.publicdomain = :publicdomain')->setParameter('publicdomain', true);
        }

        if ($publicdomain == 'include') {
            return $this->orWhere('m.publicdomain = :publicdomain')->setParameter('publicdomain', true);
        }
    }

    public function filterAuthor($author)
    {
        if ($author !== false) {
            return $this->andWhere('m.author = :author')->setParameter('author', $author);
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
////                    $uid = \UserUtil::getIdFromName($search);
//                    $uid = $uid !== false ? $uid : 0;
//                    return $this->filterAuthor($uid);
//                }
//                break;
            case 'name':
                return $this->andWhere('m.name LIKE :search')->setParameter('search', '%' . $search . '%');
        }
    }

    public function sort($sortBy, $sortOrder)
    {
        return $this->orderBy('m.' . $sortBy, $sortOrder);
    }
}