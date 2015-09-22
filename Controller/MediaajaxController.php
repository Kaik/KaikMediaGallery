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
use Kaikmedia\GalleryModule\Util\Settings as Settings;

/**
 * @Route("/ajax/media")
 */
class MediaajaxController extends AbstractController
{
   
    /**
     * @Route("/get/", options={"expose"=true})
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
    public function getAction(Request $request)
    {
        // Security check
        if (!UserUtil::isLoggedIn() || !SecurityUtil::checkPermission($this->name.'::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
    
        $original_id = $request->query->get('original', false);
        $relation_id = $request->query->get('relation', false);
        
        $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');        
        
        if ($relation_id != false){
            $relation = $this->entityManager
            ->getRepository('Kaikmedia\GalleryModule\Entity\MediaRelationsEntity')
            ->find($relation_id);
            
            $template = $this->renderView('KaikmediaGalleryModule:Media:media.html.twig', array(
                'relation' => $relation,
                'original' => $relation->getOriginal(),
                'settings' => ModUtil::getVar($this->name)
            ));            
            
        }elseif ($original_id != false){
            $original = $this->entityManager
            ->getRepository('Kaikmedia\GalleryModule\Entity\MediaEntity')
            ->find($original_id);
            
            $template = $this->renderView('KaikmediaGalleryModule:Media:media.html.twig', array(
                'relation' => false,
                'original' => $original,
                'settings' => ModUtil::getVar($this->name)
            ));            
            
        }else {
        	
        	//not found $template
                      
        }

    
        $response = new Response(json_encode(array('template' => $template)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    } 
    
    /**
     * @Route("/edit/", options={"expose"=true})
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
    public function editAction(Request $request)
    {
        // Security check
        if (!UserUtil::isLoggedIn() || !SecurityUtil::checkPermission($this->name.'::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
           
              
        $original_id = $request->query->get('original', false);
        $relation_id = $request->query->get('relation', false);        
        
        
        $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');       
        
        if ($relation_id != false){
        	$relation = $this->entityManager
        	->getRepository('Kaikmedia\GalleryModule\Entity\MediaRelationsEntity')
        	->find($relation_id);
        
        	//error if not found
        	
        	$options['isXmlHttpRequest'] = $request->isXmlHttpRequest();
        	$relation_form = $this->createForm('media_relation', $relation, $options);        	       	
        	
        	$template = $this->renderView('KaikmediaGalleryModule:Media:modify.relation.html.twig', array(
        			'form' => $relation_form->createView(),
        			'relation' => $relation,
        			'original' => $relation->getOriginal(),
        			'settings' => ModUtil::getVar($this->name)
        	));
        
        }elseif ($original_id != false){
        	$original = $this->entityManager
        	->getRepository('Kaikmedia\GalleryModule\Entity\MediaEntity')
        	->find($original_id);
        	
        	//error if not found
        		
        	$options['isXmlHttpRequest'] = $request->isXmlHttpRequest();
        	$original_form = $this->createForm('media', $original, $options);
        	 
        	       	
        	$template = $this->renderView('KaikmediaGalleryModule:Media:modify.media.html.twig', array(
        			'form'=> $original_form->createView(),
        			'relation' => false,
        			'original' => $original,
        			'settings' => ModUtil::getVar($this->name)
        	));
        
        }else {
        	 
        	//not found $template
        
        }
        
       
        $response = new Response(json_encode(array('template' => $template)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
        
        /*
        if($original == null){

        }else{
            $album = $this->entityManager
            ->getRepository('Kaikmedia\GalleryModule\Entity\AlbumEntity')
            ->find($id);
        }
        


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
         */
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