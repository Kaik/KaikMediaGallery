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
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        return $this->render('KaikmediaGalleryModule:User:index.html.twig');
    }
}