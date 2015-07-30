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

/**
 */
class MediaSelectController extends AbstractController
{  
    /**
     * This function generate 
     *
     * @return RedirectResponse
     */
    public function mediaselectAction(Request $request, $bundle = null, $id = null)
    {
        // Security check
        if (! SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
        
        $thisMedia = false;
        $publicMedia = $this->get('doctrine.entitymanager')->getRepository('Kaikmedia\GalleryModule\Entity\MediaEntity')->getAll(array('publicdomain'=> true));
        $userMedia = $this->get('doctrine.entitymanager')->getRepository('Kaikmedia\GalleryModule\Entity\MediaEntity')->getAll(array('author'=> UserUtil::getVar('uid')));        
        
        
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('KaikmediaGalleryModule:Media:mediaselect.html.twig', array(
            'bundle' => $bundle,
            'id'     => $id,
            'publicMedia' => $publicMedia,
            'thisMedia'   => $thisMedia,
            'userMedia'   => $userMedia
        ));
    }
}