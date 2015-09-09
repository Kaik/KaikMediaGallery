<?php
/**
 * Copyright (c) KaikMedia.com 2014
 */
namespace Kaikmedia\GalleryModule\Util;

use ModUtil;
use ServiceUtil;
use System;
use UserUtil;
use Doctrine\ORM\EntityManager;

class MediaRelations
{
    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    private $queryBuilder;
    /**
     * @var EntityManager
     */
    private $em;    
    
    /**
     * construct
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        
    }

   
    public function addNew($obj_name, $obj_id, $id)
    {
    
        return true;
    
    }
}