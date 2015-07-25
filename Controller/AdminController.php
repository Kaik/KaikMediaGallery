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
 * @Route("/admin")
 */
class AdminController extends AbstractController
{

    public function postInitialize()
    {
        $this->view->setCaching(false);
    }

    /**
     * Route not needed here because this is a legacy-only method
     * The default entrypoint.
     * 
     * @return RedirectResponse
     */
    public function mainAction()
    {
        return new RedirectResponse($this->get('router')->generate('kaikmediagallerymodule_admin_info', array(), RouterInterface::ABSOLUTE_URL));
    }

    /**
     * @Route("")
     * the main administration function
     * 
     * @return RedirectResponse
     */
    public function indexAction(Request $request)
    {
        // Security check
        if (! SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
        
        return new RedirectResponse($this->get('router')->generate('kaikmediagallerymodule_admin_info', array(), RouterInterface::ABSOLUTE_URL));
    }
    
    /**
     * @Route("/info")
     * the main administration function
     *
     * @return RedirectResponse
     */
    public function infoAction(Request $request)
    {
        // Security check
        if (! SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
        /*
         $a = array();
         // Get startnum and perpage parameter for pager
         $a['page'] = $page;
         $a['limit'] = $request->query->get('limit', 15);
         $a['title'] = $request->query->get('title', false);
         $a['online'] = $request->query->get('online', false);
         $filters = array();
         $form = $this->createForm('pagesfilterform', $filters, array(
         'action' => $this->get('router')
         ->generate('kaikmediapagesmodule_admin_manager', array(), RouterInterface::ABSOLUTE_URL),
         'limit' => $a['limit'],
         'title' => $a['title'],
         'online' => $a['online']
         ));
         $form->handleRequest($request);
    
         if ($form->isValid()) {
         $data = $form->getData();
         $a['limit'] = $data['limit'] ? $data['limit'] : $a['limit'];
         $a['title'] = $data['title'] ? $data['title'] : $a['title'];
         $a['online'] = $data['online'] ? $data['online'] : $a['online'];
         }
    
         // Get parameters from whatever input we need.
         $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');
         $pages = $this->entityManager->getRepository('Kaikmedia\PagesModule\Entity\PagesEntity')->getAll($a);
         */
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('KaikmediaGalleryModule:Admin:info.html.twig', array(
            //    'ZUserLoggedIn' => \UserUtil::isLoggedIn(),
            //    'pages' => $pages,
            //   'form' => $form->createView(),
            //    'thisPage' => $a['page'],
            //    'maxPages' => ceil($pages->count() / $a['limit'])
        ));
    }
    
    /**
     * @Route("/media/addnew")
     * Modify site information.
     *
     * @param Request $request
     * 
     */
    public function addnewAction(Request $request)
    {
        // Security check
        if (! UserUtil::isLoggedIn() || ! SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }

        $media = new Media();

        $form = $this->createForm('media', $media);
    
        $form->handleRequest($request);
    
        $em = $this->getDoctrine()->getManager();
        if ($form->isValid()) {
            $em->persist($media);
            $em->flush();
            $request->getSession()
            ->getFlashBag()
            ->add('status', "Media added!");
    
            return $this->redirect($this->generateUrl('kaikmediagallerymodule_admin_mediastore'));
        }
    
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('KaikmediaGalleryModule:Admin:modify.media.html.twig', array(
            'form' => $form->createView(),
            'settings' => ModUtil::getVar($this->name)
        ));
    }   
     
    /**
     * @Route("/media/modify/{id}", requirements={"id" = "\d+"})
     * Modify site information.
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
    public function modifyAction(Request $request, $id = null)
    {
        // Security check
        if (! UserUtil::isLoggedIn() || ! SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
    
        if ($id == null) {
            // create a new customer
            $media = new Media();
        } else {
            $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');
            $media = $this->entityManager->getRepository('Kaikmedia\GalleryModule\Entity\MediaEntity')->getOneBy(array(
                'id' => $id
            ));
        }
    
        $form = $this->createForm('media', $media);
    
        $form->handleRequest($request);
    
        /**
         *
         * @var \Doctrine\ORM\EntityManager $em
        */
        $em = $this->getDoctrine()->getManager();
        if ($form->isValid()) {
            $em->persist($media);
            $em->flush();
            $request->getSession()
            ->getFlashBag()
            ->add('status', "Media saved!");
    
            return $this->redirect($this->generateUrl('kaikmediagallerymodule_admin_mediastore'));
        }
    
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('KaikmediaGalleryModule:Admin:modify.media.html.twig', array(
            'form' => $form->createView(),
            'media' => $media,
            'settings' => ModUtil::getVar($this->name)
        ));
    }

    /**
     * @Route("/mediaobjmap/{page}", requirements={"page" = "\d*"}, defaults={"page" = 1})
     * the main administration function
     *
     * @return RedirectResponse
     */
    public function mediaobjmapAction(Request $request, $page)
    {
        // Security check
        if (! SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
    
        $a = array();
        // Get startnum and perpage parameter for pager
        $a['page'] = $page;
        $a['limit'] = $request->query->get('limit', 15);
        $a['name'] = $request->query->get('name', false);
    
        $form = $this->createFormBuilder($a)
        ->add('name', 'text', array('required'  => false))
        ->add('filter', 'submit', array('label' => 'Filter'))
        ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isValid()) {
            $data = $form->getData();
            $a['limit'] = $data['limit'] ? $data['limit'] : $a['limit'];
            $a['name'] = $data['name'] ? $data['name'] : $a['name'];
        }
    
        // Get parameters from whatever input we need.
        $mediaobjmap = $this->get('doctrine.entitymanager')->getRepository('Kaikmedia\GalleryModule\Entity\MediaObjMapEntity')->getAll($a);
    
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('KaikmediaGalleryModule:Admin:mediaobjmap.html.twig', array(
            'mediaobjmap' => $mediaobjmap,
            'settings' => ModUtil::getVar($this->name),
            'form' => $form->createView(),
            'thisPage' => $a['page'],
            'maxPages' => ceil($mediaobjmap->count() / $a['limit'])
        ));
    }    
    
    /**
     * @Route("/mediastore/{page}", requirements={"page" = "\d*"}, defaults={"page" = 1})
     * the main administration function
     * 
     * @return RedirectResponse
     */
    public function mediastoreAction(Request $request, $page)
    {
        // Security check
        if (! SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
        
        $a = array();
        // Get startnum and perpage parameter for pager
        $a['page'] = $page;
        $a['limit'] = $request->query->get('limit', 15);
        $a['name'] = $request->query->get('name', false);
        
        $form = $this->createFormBuilder($a)
        ->add('name', 'text', array('required'  => false))
        ->add('filter', 'submit', array('label' => 'Filter'))
        ->getForm();        
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $data = $form->getData();
            $a['limit'] = $data['limit'] ? $data['limit'] : $a['limit'];
            $a['name'] = $data['name'] ? $data['name'] : $a['name'];
        }
        
        // Get parameters from whatever input we need.
        $media = $this->get('doctrine.entitymanager')->getRepository('Kaikmedia\GalleryModule\Entity\MediaEntity')->getAll($a);
        
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('KaikmediaGalleryModule:Admin:mediastore.html.twig', array(
            'media' => $media,
            'settings' => ModUtil::getVar($this->name),
            'form' => $form->createView(),
            'thisPage' => $a['page'],
            'maxPages' => ceil($media->count() / $a['limit'])
        ));
    }

    /**
     * @Route("/preferences")
     * 
     * @return Response symfony response object
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function preferencesAction(Request $request)
    {
        // Security check
        if (! SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
               
        $mod_vars = ModUtil::getVar($this->name);
        
        $form = $this->createFormBuilder($mod_vars)
            ->add('itemsperpage', 'integer', array('label' => 'Items per page'))
            ->add('upload_dir', 'text')
            ->add('upload_max_media_size', 'text')
            ->add('upload_max_total_size', 'text')
            ->add('upload_allowed_ext', 'text')
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $data = $form->getData();
            foreach ($data as $key => $value) {
                ModUtil::setVar($this->name, $key, $value);
            }
        }
        
        $request->attributes->set('_legacy', true); // forces template to render inside old them
        return $this->render('KaikmediaGalleryModule:Admin:preferences.html.twig', array(
             'form' => $form->createView()
        ));
    }
}