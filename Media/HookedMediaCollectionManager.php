<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Media;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityManager;
use Kaikmedia\GalleryModule\Settings\SettingsManager;
use Kaikmedia\GalleryModule\Entity\QueryBuilder\MediaRelationsQueryBuilder;
use Zikula\ExtensionsModule\Api\VariableApi;

/**
 * HookedMediaCollectionManager
 *
 * @author Kaik
 */
class HookedMediaCollectionManager
{
    /*
     * Items
    */
    private $collection = [];

    /*
     * Paging
     */
    private $pager = false;

    private $page = 1;

    private $limit = -1;

    private $count = 0;

    private $offset = 0;
    
    /*
     * Filters
     */
    private $filters = [];

    /*
     * Sorting
     */
    private $sortBy = 'createdAt';

    private $sortOrder = 'DESC';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var SettingsManager
     */
    private $settingsManager;

    /**
     * Constructor
     *
     * @todo add
     */
    public function __construct(
        EntityManager $entityManager,
        SettingsManager $settingsManager
    ) {
        $this->name = 'KaikmediaGalleryModule';
        $this->entityManager = $entityManager;
        $this->settingsManager = $settingsManager;
        $this->setDefaultFilters();
    }

    /*
     * Default filter settings for online
     *
     */
    protected function setDefaultFilters()
    {
        $this->filters = [
            'id'                => false,
            'hookedModule'  => false,
            'hookedAreaId'      => false,
            'hookedObjectId'    => false,
            'hookedUrlObject'   => false,
            'feature'           => false,
            'media'             => false,
        ];
    }

    public function buildCollection()
    {
        $this->qb = new MediaRelationsQueryBuilder($this->entityManager);
        $this->qb->select('mo');
        $this->qb->from('Kaikmedia\GalleryModule\Entity\Relations\HooksRelationsEntity', 'mo');
//        $this->qb->leftJoin('p.categoryAssignments', 'c');

        return $this;
    }

    public function getCollectionElementById($id)
    {
        return $this->entityManager->find('Kaikmedia\GalleryModule\Entity\Relations\HooksRelationsEntity', $id);
    }

    /*
     *  Setters
     */

    /*
     * Items
     * Should not be used externally
     *
     */
    private function setItems($items)
    {
        $this->collection = $items;

        return $this;
    }
    
    /*
     * Items
     * Should not be used externally
     *
     */
    public function setNewCollection($items)
    {
        $this->setItems($items);

        return $this;
    }

    /*
     * Paging and pager
     *
     */
    public function setEnablePager($pager)
    {
        $this->pager = $pager;

        return $this;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /*
     * Sorting
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = !empty($sortBy) ? $sortBy : $this->sortBy;

        return $this;
    }

    public function setSortOrder($sortOrder)
    {
        $allowedOptions = ['asc', 'ASC', 'desc', 'DESC'];
        $this->sortOrder = in_array($sortOrder, $allowedOptions) ? strtoupper($sortOrder) : $this->sortOrder ;

        return $this;
    }

    /*
     * Filters
     *
     * Set array of filters
     *
     */
    public function setFilters($filters)
    {
        if (!is_array($filters)) {
            return $this;
        }

        foreach ($filters as $key => $value) {
            // key check
            if (!array_key_exists($key, $this->filters)) {
                continue;
            }
            // value check
            if ('' === $value) {
                continue;
            }
            // method check
            $fn = 'set' . ucfirst($key);
            if (!method_exists($this, $fn)) {
                continue;
            }

            $this->$fn($value);
        }

        return $this;
    }

    public function setId($id)
    {
        $this->filters['id'] = $id;

        return $this;
    }

    public function setHookedModule($hookedModule)
    {
        $this->filters['hookedModule'] = $hookedModule;

        return $this;
    }

    public function setHookedAreaId($hookedAreaId)
    {
        $this->filters['hookedAreaId'] = $hookedAreaId;

        return $this;
    }

    public function setHookedObjectId($hookedObjectId)
    {
        $this->filters['hookedObjectId'] = $hookedObjectId;

        return $this;
    }

    public function setHookedUrlObject($hookedUrlObject)
    {
        $this->filters['hookedUrlObject'] = $hookedUrlObject;

        return $this;
    }
    
    public function setFeature($feature)
    {
        $this->filters['feature'] = $feature;

        return $this;
    }
    
    public function setMedia($media)
    {
        $this->filters['media'] = $media;

        return $this;
    }

    /*
     *  Getters
     */
    public function getItems()
    {
        return $this->collection;
    }

    // @todo duplicated
    public function getItemsArray()
    {
        return $this->collection;
    }

    /*
     * Paging
     */
    public function getEnablePager()
    {
        return $this->pager;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getCount()
    {
        return $this->count;
    }

    /*
     * Sorting
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /*
     * Filters
     */
    public function getFilters()
    {
        return $this->filters;
    }
    
    /*
     * main action
     *
     */
    public function load()
    {
        // apply filters
        $this->qb->addFilters($this->getFilters());
        // search
//        $this->qb->addSearch($s);
        // sort
        $this->qb->sort($this->sortBy, $this->sortOrder);

        $query = $this->qb->getQuery();

        if ($this->limit > 0) {
            $query->setMaxResults($this->limit);
        }

        if ($this->pager) {
            $query->setFirstResult($this->limit * ($this->page - 1) + $this->offset);
            $paginator = new Paginator($query);
            $this->count = count($paginator);
            $this->collection = $paginator;
        } else {
            $this->collection = $query->getResult();
        }

        return $this;
    }
    
    /*
     * Actions
     */
    public function removeCollection()
    {
        foreach ($this->collection as $relation) {
            $this->entityManager->remove($relation);
            $this->entityManager->flush();
        }
        
        return $this;
    }
    
    /*
     * Actions
     */
    public function storeCollection()
    {
        foreach ($this->collection as $relation) {
            if ($relation->getId() > 0) {
                $this->entityManager->flush($relation);
            } else {
                $this->entityManager->persist($relation);
                $this->entityManager->flush();
            }
        }
        
        return $this;
    } 
}
