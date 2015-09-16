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
use HookUtil;
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
use Kaikmedia\GalleryModule\Entity\AlbumEntity as Album;

/**
 * @Route("/ajax/admin")
 */
class AdminajaxController extends AbstractController
{  
    /**
     * @Route("/saveobjpreferences", options={"expose"=true})
     * @Method("POST")
     *
     * @return Response symfony response object
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function objpreferencesAction(Request $request)
    {
        // Security check
        if (! SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
               
        $obj_json = $request->request->get('settings', false);
        
        $settings_arr = json_decode($obj_json);
        ModUtil::setVar($this->name, 'obj_settings', $settings_arr);
        
        $response = new Response(json_encode($result = array(
            'response' => $settings_arr
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    public function postInitialize()
    {
        $this->view->setCaching(false);
    }
    
    /**
     * Create and configure the view for the controller.
     *
     * NOTE: This is necessary because the Zikula_Controller_AbstractAjax overrides this method located in Zikula_AbstractController.
     */
    protected function configureView()
    {
        $this->setView();
        $this->view->setController($this);
        $this->view->assign('controller', $this);
    }
    
}