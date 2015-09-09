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
use Zikula\Core\Response\Ajax\NotFoundResponse;
use Zikula\Core\Response\Ajax\ForbiddenResponse;
use Zikula\Core\Response\Ajax\BadDataResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Kaikmedia\GalleryModule\Entity\MediaEntity as Media;
use Kaikmedia\GalleryModule\Entity\AlbumEntity;

/**
 * @Route("/ajax/albums")
 */
class AlbumsajaxController extends AbstractController
{


    /**
     * @Route("/refresh/", options={"expose"=true})
     * @Method("GET")
     * Modify aplicant information.
     *
     * @param Request $request
     * @param integer $id
     *
     * Parameters passed via GET:
     * --------------------------------------------------
     * string   uname The user name of the account for which profile information should be modified; defaults to the uname of the current user.
     * dynadata array The modified profile information passed into this function in case of an error in the update function.
     *
     * @return RedirectResponse|string The rendered template output.
     *
     * @throws AccessDeniedException on failed permission check
     */
    public function refreshAction(Request $request)
    {
        // Get parameters from whatever input we need.
        $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');
        $albums = $this->entityManager
        ->getRepository('Kaikmedia\GalleryModule\Entity\AlbumEntity')
        ->getAlbumJsTree();
    
        $response = new Response(json_encode($albums));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }    
    
    /**
     * @Route("/getalbum/", options={"expose"=true})
     * @Method("GET")
     * Modify aplicant information.
     *
     * @param Request $request
     * @param integer $id
     *
     * Parameters passed via GET:
     * --------------------------------------------------
     * string   uname The user name of the account for which profile information should be modified; defaults to the uname of the current user.
     * dynadata array The modified profile information passed into this function in case of an error in the update function.
     *
     * @return RedirectResponse|string The rendered template output.
     *
     * @throws AccessDeniedException on failed permission check
     */
    public function getAlbumAction(Request $request)
    {
        // Security check
        if (!UserUtil::isLoggedIn() || !SecurityUtil::checkPermission($this->name.'::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
    
        $id = $request->query->get('id', false);
        // Get parameters from whatever input we need.
        $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');
        $album = $this->entityManager
        ->getRepository('Kaikmedia\GalleryModule\Entity\AlbumEntity')
        ->find($id);
    
        $template = $this->renderView('KaikmediaGalleryModule:Album:display.details.html.twig', array(
            'album' => $album,
            'settings' => ModUtil::getVar($this->name)
        ));
    
        $response = new Response(json_encode(array('template' => $template)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    } 
    
    /**
     * @Route("/edit/{id}", options={"expose"=true})
     * @Method({"GET", "POST"})
     * Modify aplicant information.
     *
     * @param Request $request
     * @param integer $id
     *
     * Parameters passed via GET:
     * --------------------------------------------------
     * string   uname The user name of the account for which profile information should be modified; defaults to the uname of the current user.
     * dynadata array The modified profile information passed into this function in case of an error in the update function.
     *
     * @return RedirectResponse|string The rendered template output.
     *
     * @throws AccessDeniedException on failed permission check
     */
    public function editAction(Request $request, $id = null)
    {
        // Security check
        if (!UserUtil::isLoggedIn() || !SecurityUtil::checkPermission($this->name.'::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
           
        $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');       
        
        if($id == null){
            // create new album
            $album = new AlbumEntity();            
            $parentId = $request->request->get('parent', 1);            
            $parent = $this->entityManager
            ->getRepository('Kaikmedia\GalleryModule\Entity\AlbumEntity')
            ->find($parentId);            
            $album->setParent($parent);
        }else{
            $album = $this->entityManager
            ->getRepository('Kaikmedia\GalleryModule\Entity\AlbumEntity')
            ->find($id);
        }
        
        $options['isXmlHttpRequest'] = $request->isXmlHttpRequest();
        $form = $this->createForm('album', $album, $options);

        if ($request->getMethod() == "POST"){
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($album);
                $em->flush();
                
                $request->getSession()
                ->getFlashBag()
                ->add('status', "Album updated!");                
                
                $template = $this->renderView('KaikmediaGalleryModule:Album:modify.album.html.twig', array(
                    'form' => $form->createView(),
                    'album' => $album));
            
                $response = new Response(json_encode(array('template' => $template)));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }else {
                    $template = $this->renderView('KaikmediaGalleryModule:Album:modify.album.html.twig', array(
                                'form' => $form->createView(),
                                'album' => $album));               
                   $response = new Response(json_encode(array('template' => $template)));
                   $response->headers->set('Content-Type', 'application/json');
                return $response; 
            }
        }
           $template = $this->renderView('KaikmediaGalleryModule:Album:modify.album.html.twig', array(
                        'form' => $form->createView(),
                        'album' => $album));               
           $response = new Response(json_encode(array('template' => $template)));
           $response->headers->set('Content-Type', 'application/json');
        return $response;           
    }
    /**
     * @Route("/move/{id}", options={"expose"=true})
     * @Method("POST")
     * Modify aplicant information.
     *
     * @param Request $request
     * @param integer $id
     *
     * Parameters passed via POST:
     * --------------------------------------------------
     * string   uname The user name of the account for which profile information should be modified; defaults to the uname of the current user.
     * dynadata array The modified profile information passed into this function in case of an error in the update function.
     *
     * @return RedirectResponse|string The rendered template output.
     *
     * @throws AccessDeniedException on failed permission check
     */
    public function moveAction(Request $request, $id = null)
    {
        // Security check
        if (!UserUtil::isLoggedIn() || !SecurityUtil::checkPermission($this->name.'::', '::', ACCESS_ADMIN)) {
            return new ForbiddenResponse($this->__('No permission for this action'));
        }
         
        $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');
    
        if($id == null){
            return new BadDataResponse($this->__('Error! Cannot determine valid album \'id\' to move.'));            
        }
        
        $album = $this->entityManager
            ->getRepository('Kaikmedia\GalleryModule\Entity\AlbumEntity')
            ->find($id);
        if(!$album){
            return new BadDataResponse($this->__('Error! Cannot find album to move.'));        
        }
        
        $parentId = $request->request->get('parent', null);
        if($parentId == null){
            return new BadDataResponse($this->__('Error! Cannot determine valid parent album \'id\' for moving.')); 
        }        
                
        $parent = $this->entityManager
            ->getRepository('Kaikmedia\GalleryModule\Entity\AlbumEntity')
            ->find($parentId);
        if(!$parent){
            return new BadDataResponse($this->__('Error! Cannot find parent album for moving.'));        
        }        
        
        $album->setParent($parent);

        $em = $this->getDoctrine()->getManager();
        $em->persist($album);
        $em->flush();

        $response = new Response(json_encode($result = array(
            'response' => true
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