<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Manager;

use ServiceUtil;
use UserUtil;
use Kaikmedia\GalleryModule\Manager\MediaRelations;


class Plugin
{     
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
    
    /**
     * This function allows you to assingn externall objects to media 
     * 
     * obj_name 
     * obj_id
     * 
     * $media_array
     *
     * @return RedirectResponse
     */
    public function assignMedia($obj_name = null, $obj_id = null, $media_arr = array())
    {
        // Security check
        if (! SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
        
        if ($obj_name == null){
            return null;
        }                
        
        if ($obj_id == null){
            return null;
        }
        
        if (is_empty($media_arr)){
            return null;
        }     

        $mediaManager = new MediaRelations($this->container->get('doctrine.entitymanager'));
        $result = array();
        foreach($media_arr as $media) {
            $result[] = $mediaManager->addNew($obj_name,$obj_id,$media);
        }
        
        return $result;
        
    }
}