<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Controller;

use ModUtil;
use System;
use SecurityUtil;
use ServiceUtil;
use UserUtil;
use PageUtil;
use Zikula\Core\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Kaikmedia\GalleryModule\Entity\MediaEntity as Media;
use Kaikmedia\GalleryModule\Entity\MediaObjMapEntity as MediaMap;
use Kaikmedia\GalleryModule\Util\Common as Utils;
use Kaikmedia\GalleryModule\Util\Settings as Settings;

/**
 */
class PluginController extends AbstractController
{  
    /**
     * This function generate 
     *
     * @return RedirectResponse
     */
    public function galleryAction(Request $request, $obj_name = null, $obj_id = null, $mode = 'all')
    {
        // Security check
        if (! SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
        
        $settings = new Settings();

        if ($obj_id == null){       	
        	$icon = false;
        	$image = false;
        	$media = false;        	
        }else{

        	$icon = $this->get('doctrine.entitymanager')
        	->getRepository('Kaikmedia\GalleryModule\Entity\MediaObjMapEntity')
        	->getOneBy(array('obj_name'=> $obj_name,
        			'type' => 'icon',
        			'obj_id' => $obj_id ));
        	
        	
        	$image = $this->get('doctrine.entitymanager')
        	->getRepository('Kaikmedia\GalleryModule\Entity\MediaObjMapEntity')
        	->getOneBy(array('obj_name'=> $obj_name,
        			'type' => 'image',
        			'obj_id' => $obj_id ));
        	
        	
        	$media = $this->get('doctrine.entitymanager')
        	->getRepository('Kaikmedia\GalleryModule\Entity\MediaObjMapEntity')
        	->getAll(array('obj_name'=> $obj_name,
        			'type' => 'media',
        			'obj_id' => $obj_id ));   
        }
        
        
        $public = $this->get('doctrine.entitymanager')->getRepository('Kaikmedia\GalleryModule\Entity\MediaEntity')->getAll(array('publicdomain'=> true));
        
        
        $user = $this->get('doctrine.entitymanager')->getRepository('Kaikmedia\GalleryModule\Entity\MediaEntity')->getAll(array('author'=> UserUtil::getVar('uid')));             
        
        
        
        
        PageUtil::addVar('javascript', "@KaikmediaGalleryModule/Resources/public/js/Kaikmedia.Gallery.Plugin.js");
        PageUtil::addVar('stylesheet', "@KaikmediaGalleryModule/Resources/public/css/gallery.plugin.css");
      
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('KaikmediaGalleryModule:Plugin:gallery.html.twig', array(
        	'mode'     => $mode,
            'settings' => $settings->getSettings(),
            'obj_name' => $obj_name,
            'obj_id'   => $obj_id,
            'icon'     => $icon,
            'image'    => $image,
            'media'    => $media,
            'public'   => $public,
            'user'     => $user
        ));
    }
}