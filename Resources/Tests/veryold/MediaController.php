<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Controller;

use DataUtil;
use ModUtil;
use SecurityUtil;
use Symfony\Component\HttpFoundation\JsonResponse;
use Zikula\Core\Response\Ajax\AjaxResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use System;
use UserUtil;
use ServiceUtil;
use ZLanguage;
use Zikula\Core\Controller\AbstractController;
use Zikula\Core\Event\GenericEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Symfony\Component\Routing\RouterInterface;
use Kaikmedia\GalleryModule\Entity\MediaEntity as File;

/**
 * @Route("/media")
 * Class MediaController
 *
 * @package Kaikmedia\GalleryModule\Controller
 */
class MediaController extends AbstractController
{
    /**
     * @Route("/modify/{id}", options={"expose"=true})
     * @Method("GET")
     * Modify media information.
     *
     * @param Request $request
     * @param integer $id
     *
     */
    public function modifyAction(Request $request, $id = null)
    {
    	// Security check
    	if (!UserUtil::isLoggedIn() || !SecurityUtil::checkPermission($this->name.'::', '::', ACCESS_ADMIN)) {
    		throw new AccessDeniedException();
    	}

        if($id == null){
	        $file = new File();
	        $file->setPromoted(false);

        }else{
	        $file = $this->get('doctrine.entitymanager')->getRepository('Kaikmedia\GalleryModule\Entity\MediaEntity')
	                    ->getOneBy(['id' => $id]);
        }

        $options = [];
        $options['isXmlHttpRequest'] = $request->isXmlHttpRequest();
        $options['action'] =  $this->get('router')->generate('kaikmediagallerymodule_media_modify', [], RouterInterface::ABSOLUTE_URL);
        $form = $this->createForm('media', $file, $options);

        //$form->bindRequest($request);

        if ($request->getMethod() == "POST"){
        	$form->handleRequest($request);
        	if ($form->isValid())
        	{
			    $em = $this->getDoctrine()->getManager();
			    $em->persist($file);
			    $em->flush();
			    $template = $file->getName();
			$response = new Response(json_encode(['template' => $template]));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
	       }else {
	           $errors = [];
	           foreach ($form->getErrors() as $key => $error) {
	               $errors[] = $error->getMessage();
	           }
	           $response = new Response(json_encode(['template' => $errors]));
	           $response->headers->set('Content-Type', 'application/json');
	           return $response;
	       }
        }

        if($mode == 'html'){
	       $template = $this->renderView('KaikmediaGalleryModule:Media:new.form.html.twig', [
            'form' => $form->createView(),
            'file' => $file]);
        }else {
        	$template['id'] = $file->getId();
        	$template['name'] = $file->getName();
        }


		$response = new Response(json_encode(['template' => $template]));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
    }

    /**
     * @Route("/add/", options={"expose"=true})
     * @Method("POST")
     * Modify aplicant information.
     *
     * @param Request $request
     * @param integer $id
     *            Parameters passed via GET:
     *            --------------------------------------------------
     *            string uname The user name of the account for which profile information should be modified; defaults to the uname of the current user.
     *            dynadata array The modified profile information passed into this function in case of an error in the update function.
     * @return RedirectResponse|string The rendered template output.
     * @throws AccessDeniedException on failed permission check
     */
    public function addAction(Request $request)
    {
        // Security check
        if (! SecurityUtil::checkPermission($this->name . '::view', '::', ACCESS_READ)) {
            throw new AccessDeniedException();
        }

        $file = new File();
        $file->setPromoted(false);
        $options = [];
        $options['isXmlHttpRequest'] = $request->isXmlHttpRequest();
        $form = $this->createForm('media', $file, $options);

        if ($request->getMethod() == "POST"){
            $a = $form->isValid();
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($file);
                $em->flush();
                $data = [];
                $data['file'] = $file->toArray();
                $data['homeurl'] = $this->getRequest()->getScheme().'://'.$this->getRequest()->getHttpHost();
                $response = new Response(json_encode($data));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }else {
	           $response = new Response(json_encode(['template' => $form->getErrorsAsString()]));
	           $response->headers->set('Content-Type', 'application/json');
	           return $response;
	       }
        }

        $response = new Response(json_encode([
            'template' => $a
        ]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/get/{file}", options={"expose"=true})
     * @Method("GET")
     * Get image.
     *
     * @param Request $request
     * @param integer $id
     *
     * @return RedirectResponse|string The rendered template output.
     * @throws AccessDeniedException on failed permission check
     */
    public function getAction(Request $request, File $file)
    {
        // Security check
        if (!SecurityUtil::checkPermission($this->name . '::view', '::', ACCESS_READ)) {
            throw new AccessDeniedException();
        }

        if (!$file){
            throw new AccessDeniedException();
        }

        $mode = 'html';
        if($mode == 'html'){
            $template = $this->renderView('KaikmediaGalleryModule:Media:element.html.twig', [
                'file' => $file]);
        }else {
            $template['id'] = $file->getId();
            $template['name'] = $file->getName();
        }

        $response = new Response(json_encode(['template' => $template]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }
}
