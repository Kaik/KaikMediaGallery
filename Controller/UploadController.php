<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright (C) 2017 KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Controller;

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

/**
 * @Route("/ajax/upload")
 * Class AplicantsAjaxController
 *
 * @package Kaikmedia\GalleryModule\Controller
 */
class UploadController extends AbstractController
{
    /**
     * @Route("/newmedia/", options={"expose"=true})
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
    public function newmediaAction(Request $request)
    {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $media = new Media();
        $options = [];
        $options['isXmlHttpRequest'] = $request->isXmlHttpRequest();
        $form = $this->createForm('media', $media, $options);

        if ($request->getMethod() == "POST") {
            $a = $form->isValid();
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($media);
                $em->flush();
                $data = [];
                $data['media'] = $media->toArray();
                // $data['homeurl'] = $this->getRequest()->getScheme().'://'.$this->getRequest()->getHttpHost();
                $response = new Response(json_encode($data));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            } else {
                $response = new Response(json_encode(['template' => $form->getErrorsAsString()]));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }

        $response = new Response(json_encode([
                    'template' => 'no post method'
        ]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}