<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Manager;

use Kaikmedia\GalleryModule\Manager\Relations;
use Doctrine\ORM\EntityManager;

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
        $this->name = 'KaikmediaGalleryModule';
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
    public function assignMedia($obj_name = null, $obj_id = null, $media_str = null)
    {
        // Security check
        if (!\SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
        
        if ($obj_name == null){
            return null;
        }                
        
        if ($obj_id == null){
            return null;
        }
        
        if ($media_str == null){
            return null;
        }     
        
        $media_arr = split(',',$media_str);
        
        $mediaManager = new Relations($this->em);
        $result = array();
        foreach($media_arr as $media) {
            $result[] = $mediaManager->addNew($obj_name,$obj_id,$media);
        }
        
        return $result;
        
    }
}