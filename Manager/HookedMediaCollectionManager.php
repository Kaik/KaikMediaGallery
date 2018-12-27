<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Manager;

//use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityManager;

//use Kaikmedia\GalleryModule\Entity\Media\ImageEntity as Media;
//use Kaikmedia\GalleryModule\Entity\Relations\HooksRelationsEntity;
//use Kaikmedia\NewsModule\Entity\NewsEntity;
//use Kaikmedia\NewsModule\Entity\CategoryAssignmentEntity;
use Kaikmedia\NewsModule\Entity\NewsQueryBuilder;
//use Kaikmedia\NewsModule\Helper\CategorisationHelper;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\Bundle\CoreBundle\HttpKernel\ZikulaHttpKernelInterface;
use Zikula\ExtensionsModule\Api\VariableApi;
//use Zikula\UsersModule\Entity\UserEntity;


/**
 * NewsCollection
 *
 * @author Kaik
 */
class HookedMediaCollectionManager
{
    /*
     * News Items
    */
    private $items = [];

    /*
     * Paging
     */
    private $pager = false;

    private $page = 1;

    private $limit = 25;

    private $count = 0;

    private $offset = 0;
    /*
     * Filters
     */
    private $filters = [];

    /*
     * Sorting
     */
    private $sortBy = 'publishedAt';

    private $sortOrder = 'DESC';

    /**
     * @var ZikulaHttpKernelInterface
     */
    private $kernel;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var VariableApi
     */
    private $variableApi;

    /**
     * @var CategorisationHelper
     */
    private $categorisationHelper;

    /**
     * Constructor
     *
     * @todo add
     */
    public function __construct(
        ZikulaHttpKernelInterface $kernel,
        RequestStack $requestStack,
        EntityManager $entityManager,
        VariableApi $variableApi,
        CategorisationHelper $categorisationHelper
    ) {
        $this->name = 'KaikmediaGalleryModule';
        $this->kernel = $kernel;
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getMasterRequest();
        $this->entityManager = $entityManager;
        $this->variableApi = $variableApi;
        $this->categorisationHelper = $categorisationHelper;
        $this->setDefaultFilters();
    }

    /*
     * Default filter settings for online
     *
     */
    protected function setDefaultFilters()
    {
        $this->filters = [
            'title'                 => false,
            'online'                => 1,
            'depot'                 => 0,
            'inmenu'                => 1,
            'inlist'                => 1,
            'author'                => false,
            'layout'                => 'any',
            'expired'               => 'published',
            'published'             => 'published',
            'language'              => 'any',
            'topic'                 => [],
//            'categoryAssignments'   => []
        ];
    }

    public function buildCollection()
    {
        $this->qb = new NewsQueryBuilder($this->entityManager);
        $this->qb->select('p');
        $this->qb->from('Kaikmedia\NewsModule\Entity\NewsEntity', 'p');
        $this->qb->leftJoin('p.categoryAssignments', 'c');

        return $this;
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
        $this->items = $items;

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
//        dump($filters);
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

    public function setTitle($title)
    {
        $this->filters['title'] = $title;

        return $this;
    }

    public function setUrltitle($urltitle)
    {
        $this->filters['urltitle'] = $urltitle;

        return $this;
    }

    public function setInlist($inlist)
    {
        $this->filters['inlist'] = $inlist;

        return $this;
    }

    public function setInmenu($inmenu)
    {
        $this->filters['inmenu'] = $inmenu;

        return $this;
    }

    public function setDeleted($deleted)
    {
        $this->filters['deleted'] = $deleted;

        return $this;
    }

    public function setAuthor($author)
    {
        $this->filters['author'] = $author !== '0' ? $author : false;

        return $this;
    }

    public function setDepot($depot)
    {
        $this->filters['depot'] = $depot;

        return $this;
    }

    public function setLayout($layout)
    {
        $this->filters['layout'] = $layout;

        return $this;
    }

    public function setOnline($online)
    {
        $this->filters['online'] = $online;

        return $this;
    }

    public function setExpired($expired)
    {
        $allowedOptions = ['published', 'expired', 'awaiting', 'unset', 'any'];
        $this->filters['expired'] = in_array($expired, $allowedOptions) ? $expired : 'published';

        return $this;
    }

    public function setPublished($published)
    {
        $allowedOptions = ['published', 'awaiting', 'unset', 'any'];
        $this->filters['published'] = in_array($published, $allowedOptions) ? $published : 'published';

        return $this;
    }

    public function setTopic($topic)
    {
//        dump($topic);
        $this->filters['topic'] = $topic;

        return $this;
    }

    /*
     *  Getters
     */
    public function getItems()
    {
        return $this->items;
    }

    // @todo duplicated
    public function getItemsArray()
    {
        return $this->items;
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
    public function getOnline()
    {
        return $this->filters['online'];
    }

    public function getTopic()
    {
        return $this->filters['topic'];
    }

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
            $this->items = $paginator;

        } else {
            $this->items = $query->getResult();
        }

        return $this;
    }
}

//
//        if ($onlyone) {
//            $item = $query->getOneOrNullResult();
//
//            return $item;
//        }
//
//        $paginator = $this->paginate($query, $page, $limit);
//
//        return $paginator;
//
//
//
//        $query = $this->queryBuilder->getQuery();
//
//
//        $onlyone = isset($args['onlyone']) ? $args['onlyone'] : false;
//        // articler
//        $page = (isset($args['page']) && $args['page'] > 1) ? $args['page'] : 1;
//        $limit = isset($args['limit']) ? $args['limit'] : 25;
//        // sort
//        $sortby = isset($args['sortby']) ? $args['sortby'] : 'id';
//        $sortorder = isset($args['sortorder']) ? $args['sortorder'] : 'DESC';
//        // filter's - sql = exact search apart from title field
//        // basics
//        $f['id']        = isset($args['id']) && $args['id'] !== '' ? $args['id'] : false;
//        $f['title']     = isset($args['title']) && $args['title'] !== '' ? $args['title'] : false;
//        $f['urltitle']  = isset($args['urltitle']) && $args['urltitle'] !== '' ? $args['urltitle'] : false;
//        $f['author']    = isset($args['author']) && $args['author'] !== '' && $args['author'] != 0 ? $args['author'] : false;
//        // statuses
//        $f['online']    = isset($args['online']) && $args['online'] !== '' ? $args['online'] : false;
//        $f['depot']     = isset($args['depot']) && $args['depot'] !== '' ? $args['depot'] : false;
//        $f['showinmenu'] = isset($args['inmenu']) && $args['inmenu'] !== '' ? $args['inmenu'] : false;
//        $f['showinlist'] = isset($args['inlist']) && $args['inlist'] !== '' ? $args['inlist'] : false;
//        // dates
//        $f['expired']   = isset($args['expired']) && $args['expired'] !== '' ? $args['expired'] : false;
//        $f['published'] = isset($args['published']) && $args['published'] !== '' ? $args['published'] : false;
//        $f['deleted']   = isset($args['deleted']) && $args['deleted'] !== '' ? $args['deleted'] : false;
//        // other
//        $f['category']  = isset($args['categoryAssignments']) && $args['categoryAssignments'] !== '' ? $args['categoryAssignments'] : false;
//        $f['language']  = isset($args['language']) && $args['language'] !== '' ? $args['language'] : false;
//        $f['layout']  = isset($args['layout']) && $args['layout'] !== '' ? $args['layout'] : false;
//
//        // search sql like%
//        $s['search']        = isset($args['search']) && $args['search'] !== '' ? $args['search'] : false;
//        $s['search_field']  = isset($args['search_field']) && $args['search_field'] !== '' ? $args['search_field'] : false;



//    private function build()
//    {
//        $this->queryBuilder = $this->entityManager->createQueryBuilder();
//        $this->queryBuilder->select('n')
//        ->from('Kaikmedia\NewsModule\Entity\NewsEntity', 'n')
//        ->leftJoin('n.categoryAssignments', 'c');
//    }
//
//    /*
//     * shortcut method
//     */
//    public function getLatest($param = null)
//    {
//        return [];
//    }
//
//    public function getNews($param = null)
//    {
//        $a = [];
//        $a['page'] = 1;
//        $a['limit'] = $this->request->query->get('limit', 25);
//        $a['online'] = 1; // this should be 1 by default
//
//        $news = $this->entityManager
//            ->getRepository('Kaikmedia\NewsModule\Entity\NewsEntity')
//            ->getAll($a);
//
//        $pager = [
//            'numitems' => $news->count(),
//            'itemsperpage' => $a['limit']
//        ];
//
//        return ['items' => $news, 'pager' => $pager];
//    }
//
//
//
//    public function setItemsPerPage($amount)
//    {
//        $this->itemsPerPage = $amount;
//    }
//
//    /**
//     * set start number
//     *
//     * @param int $startNumber Start number
//     *
//     * @return void
//     */
//    public function setStartNumber($startNumber)
//    {
//        $this->startNumber = $startNumber - 1;
//    }
//
//    /**
//     * set order
//     *
//     * @param string $orderBy E.g. titles
//     * @param string $orderDirection ASC/DESC
//     */
//    public function setOrder($orderBy, $orderDirection = 'ASC')
//    {
//        $this->queryBuilder->orderBy('n.' . $orderBy, $orderDirection);
//    }
//
//    /**
//     * set language
//     *
//     * @param string $language Language code
//     */
//    public function setLanguage($language)
//    {
//        if (!empty($language)) {
//            $this->queryBuilder->andWhere('n.language = :language')->setParameter('language', $language);
//        }
//    }
//
//    /**
//     * set category
//     *
//     * @param mixed $category Category id
//     *
//     * @return void
//     */
//    public function setCategory($category)
//    {
//        $subQb = $this->em->createQueryBuilder();
//        $categorySubQuery = $subQb->select('pc')
//        ->from('ZikulaCategoriesModule:CategoryEntity', 'pc');
//        if (is_array($category)) {
//            $categorySubQuery->where('pc.id in (:categories)');
//        } else {
//            if (!empty($category)) {
//                $categorySubQuery->where('pc.id = :categories');
//            }
//        }
//        $this->queryBuilder
//        ->andWhere($this->queryBuilder->expr()->in('c.category', $categorySubQuery->getDQL()))
//        ->setParameter('categories', $category);
//    }
//
//    public function setFilterBy(array $filterData)
//    {
//        if (!empty($filterData['language'])) {
//            $this->setLanguage($filterData['language']);
//        }
//        if (isset($filterData['categoryAssignments']) && ($filterData['categoryAssignments'] instanceof ArrayCollection) && !$filterData['categoryAssignments']->isEmpty()) {
//            $categoryIds = [];
//            foreach ($filterData['categoryAssignments'] as $pagesCategoryEntity) {
//                $categoryIds[] = $pagesCategoryEntity->getCategory()->getId();
//            }
//            $this->setCategory($categoryIds);
//        }
//    }
//
//    /**
//     * return array of pages
//     *
//     * @return PageEntity[]
//     */
//    public function get()
//    {
//        $query = $this->queryBuilder->getQuery();
//        if ($this->itemsPerPage > 0) {
//            $query->setMaxResults($this->itemsPerPage);
//        }
//        if ($this->pager) {
//            $query->setFirstResult($this->startNumber);
//            $paginator = new Paginator($query);
//            $this->numberOfItems = count($paginator);
//            return $paginator;
//        } else {
//            return $query->getResult();
//        }
//    }
//
//    /**
//     * enable Pager
//     */
//    public function enablePager()
//    {
//        $this->pager = true;
//    }
//
//    /**
//     * return page as array
//     *
//     * @return array
//     */
//    public function getPager()
//    {
//        return ['itemsperpage' => $this->itemsPerPage, 'numitems' => $this->numberOfItems];
//    }