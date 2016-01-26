<?php

/**
 * Copyright (c) KaikMedia.com 2015
 */

namespace Kaikmedia\GalleryModule\Controller;

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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Kaikmedia\GalleryModule\Media\MediaHandlersManager;


/**
 * @Route("/media")
 */
class MediaController extends AbstractController {

    /**
     * @Route(
     *     "/get/{urltitle}.{_format}",
     *     defaults={"_format": "html"},
     *     requirements={
     *         "_format": "html|json"
     *     },
     *      options={"expose"=true}
     * )
     * @Method("GET")
     * Get media information.
     *
     * @param string $urltitle
     *
     * Parameters passed via GET:
     * --------------------------------------------------
     * string  urltitle mediaurl title.
     * string _format response format.
     *
     * @return RedirectResponse|string The rendered template output.
     *
     * @throws AccessDeniedException on failed permission check
     */
    public function getAction(Request $request, $_format, $urltitle) {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }


        
        
        
        //json
        if ($_format == 'json') {
            $data = array(
                'urltitle' => $urltitle,
                '_format' => $_format
            );

            $response = new JsonResponse($data);

            return $response;
        }
        
        //html
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('KaikmediaGalleryModule:Media:get.html.twig', [
                    'ZUserLoggedIn' => \UserUtil::isLoggedIn(),
        ]);        
    }

    /**
     * @Route(
     *     "/create/{type}/{_format}",
     *     defaults={"_format": "json", "type": "unknow"},
     *     requirements={
     *         "_format": "json"
     *     },
     *      options={"expose"=true}
     * )
     * 
     * Get media information.
     *
     * @param string $urltitle
     *
     * Parameters passed via GET:
     * --------------------------------------------------
     * string  urltitle mediaurl title.
     * string _format response format.
     *
     * @return RedirectResponse|string The rendered template output.
     *
     * @throws AccessDeniedException on failed permission check
     */
    public function createAction(Request $request, $type, $_format) {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $mediaManager = $this->get('kaikmedia_gallery_module.media_manager')->create($type);
        $mediaItem = $mediaManager->getMediaItem();
        $formClass = $mediaManager->getForm();
        
        $form = $this->createForm($formClass, $mediaItem, ['isXmlHttpRequest' => $request->isXmlHttpRequest()]);        
        
        $errors = false;
        if ($request->getMethod() == "POST"){
            $form->handleRequest($request);
          //  if ($form->isValid())
          //  {
                $em = $this->getDoctrine()->getManager();
                $em->persist($mediaItem);
                $em->flush();        
          //  } else {
           //     $errors = (string) $form->getErrors(true, false);
          //  }
        }
            
            
        //json
        if ($_format == 'json') {
            $data = [
                'media' => $mediaItem,
                'errors' => $errors,               
                '_format' => $_format
            ];

            $response = new JsonResponse($data);

            return $response;
        }
        
        //html
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('KaikmediaGalleryModule:Media:create.html.twig', [
                    'ZUserLoggedIn' => \UserUtil::isLoggedIn(),
        ]);        
    }    
    
}
