<?php
/**
 * Copyright (c) KaikMedia.com 2014
 */
namespace Kaikmedia\GalleryModule\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Core\Controller\AbstractController;
use Zikula\Core\Event\GenericEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Symfony\Component\Routing\RouterInterface;

class UserController extends AbstractController
{

    /**
     * @Route("/index")
     * 
     * @todo online defailt in repository
     * Display pages list.
     * @throws AccessDeniedException on failed permission check
     */
    public function indexAction(Request $request)
    {
        // Security check
        if (!\SecurityUtil::checkPermission($this->name . '::view', '::', ACCESS_READ)) {
            throw new AccessDeniedException();
        }
    
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        
        return $this->render('KaikmediaGalleryModule:User:index.html.twig');
    }
}
