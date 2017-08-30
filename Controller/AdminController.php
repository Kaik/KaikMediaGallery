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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Core\Controller\AbstractController;
use Zikula\ThemeModule\Engine\Annotation\Theme;
use Kaikmedia\GalleryModule\Entity\AlbumEntity as Album;
use Kaikmedia\GalleryModule\Entity\Media\ImageEntity as Media;
use Kaikmedia\GalleryModule\Form\Media\MediaType;
use Kaikmedia\GalleryModule\Form\Settings\SettingsType;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/index")
     * @Theme("admin")
     *
     * @return RedirectResponse
     */
    public function indexAction(Request $request)
    {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        return new RedirectResponse($this->get('router')->generate('kaikmediagallerymodule_admin_info', [], RouterInterface::ABSOLUTE_URL));
    }

    /**
     * @Route("/info")
     * @Theme("admin")
     * the main administration function
     *
     * @return RedirectResponse
     */
    public function infoAction(Request $request)
    {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        return $this->render('KaikmediaGalleryModule:Admin:info.html.twig', [
        ]);
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
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $media = new Media();

        $form = $this->createForm('media', $media);

        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isValid()) {
            $em->persist($media);

            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            $file = $form->has('file') ? $form->get('file')->getData() : null;
            $uploadableManager->markEntityToUpload($media, $file);

            $em->flush();
            $request->getSession()
                    ->getFlashBag()
                    ->add('status', "Media added!");
            return $this->redirect($this->generateUrl('kaikmediagallerymodule_admin_mediastore'));
        }

        return $this->render('KaikmediaGalleryModule:Admin:modify.media.html.twig', [
                    'form' => $form->createView(),
                    'settings' => $this->getVars()
        ]);
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
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        if ($id == null) {
            // create a new customer
            $media = new Media();
        } else {
            $media = $this->get('doctrine.entitymanager')->getRepository('Kaikmedia\GalleryModule\Entity\MediaEntity')->getOneBy([
                'id' => $id
            ]);
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

        return $this->render('KaikmediaGalleryModule:Admin:modify.media.html.twig', [
                    'form' => $form->createView(),
                    'media' => $media,
                    'settings' => $this->getVars()
        ]);
    }

    /**
     * @Route("/albums")
     * @Theme("admin")
     * the main administration function
     *
     * @return RedirectResponse
     */
    public function albumsAction(Request $request)
    {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $repo = $this->get('doctrine.entitymanager')->getRepository('Kaikmedia\GalleryModule\Entity\AlbumEntity');
        $albumTree = $repo->getAlbumJsTree();
        $album = $repo->find(1);

//        \PageUtil::addVar('javascript', "/web/jstree/dist/jstree.min.js");
//        \PageUtil::addVar('stylesheet', "/web/jstree/dist/themes/default/style.min.css");

        return $this->render('KaikmediaGalleryModule:Admin:albums.html.twig', [
                    'album' => $album,
                    'albumTree' => $albumTree,
                    'settings' => $this->getVars()
        ]);
    }

    /*
      $food = $repo->findOneByTitle('Top');

      $fruits = new Album();
      $fruits->setTitle('Modules');
      $fruits->setParent($food);

      $this->get('doctrine.entitymanager')->persist($food);
      $this->get('doctrine.entitymanager')->persist($fruits);

      $this->get('doctrine.entitymanager')->flush();
     */

    /**
     * @Route("/mediarelations/{page}", requirements={"page" = "\d*"}, defaults={"page" = 1})
     * the main administration function
     *
     * @return RedirectResponse
     */
    public function mediarelationsAction(Request $request, $page)
    {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $a = [];
        // Get startnum and perpage parameter for pager
        $a['page'] = $page;
        $a['limit'] = $request->query->get('limit', 15);
        $a['name'] = $request->query->get('name', false);

        $form = $this->createFormBuilder($a)
                ->add('name', 'text', ['required' => false])
                ->add('filter', 'submit', ['label' => 'Filter'])
                ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $a['limit'] = $data['limit'] ? $data['limit'] : $a['limit'];
            $a['name'] = $data['name'] ? $data['name'] : $a['name'];
        }

        // Get parameters from whatever input we need.
        $mediarelations = $this->get('doctrine.entitymanager')->getRepository('Kaikmedia\GalleryModule\Entity\MediaRelationsEntity')->getAll($a);

        return $this->render('KaikmediaGalleryModule:Admin:mediarelations.html.twig', [
                    'mediarelations' => $mediarelations,
                    'settings' => $this->getVars(),
                    'form' => $form->createView(),
                    'thisPage' => $a['page'],
                    'maxPages' => ceil($mediarelations->count() / $a['limit'])
        ]);
    }

    /**
     * @Route("/mediastore/{page}", requirements={"page" = "\d*"}, defaults={"page" = 1})
     * the main administration function
     *
     * @return RedirectResponse
     */
    public function mediastoreAction(Request $request, $page)
    {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $a = [];
        // Get startnum and perpage parameter for pager
        $a['page'] = $page;
        $a['limit'] = $request->query->get('limit', 15);
        $a['name'] = $request->query->get('name', false);

        $form = $this->createFormBuilder($a)
                ->add('name', 'text', ['required' => false])
                ->add('filter', 'submit', ['label' => 'Filter'])
                ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $a['limit'] = $data['limit'] ? $data['limit'] : $a['limit'];
            $a['name'] = $data['name'] ? $data['name'] : $a['name'];
        }

        // Get parameters from whatever input we need.
        $media = $this->get('doctrine.entitymanager')->getRepository('Kaikmedia\GalleryModule\Entity\MediaEntity')->getAll($a);

        return $this->render('KaikmediaGalleryModule:Admin:mediastore.html.twig', [
                    'media' => $media,
                    'settings' => $this->getVars(),
                    'form' => $form->createView(),
                    'thisPage' => $a['page'],
                    'maxPages' => ceil($media->count() / $a['limit'])
        ]);
    }

    /**
     * @Route("/preferences")
     * @Theme("admin")
     *
     * @return Response symfony response object
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function preferencesAction(Request $request)
    {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $settingsManager = $this->get('kaikmedia_gallery_module.settings_manager');

        $form = $this->createForm(new SettingsType(), $settingsManager->getSettingsForForm(), ['isXmlHttpRequest' => $request->isXmlHttpRequest()]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            if (!$settingsManager->setSettings($form->get('settings')->getData())) {
                $request->getSession()
                        ->getFlashBag()
                        ->add('error', 'Error! Settings not set! Please try again');
            } else {
                $request->getSession()
                        ->getFlashBag()
                        ->add('status', 'Settings set.');
                if (!$settingsManager->saveSettings()) {
                    $request->getSession()
                            ->getFlashBag()
                            ->add('error', 'Error! Settings not saved! Please try again');
                } else {
                    $request->getSession()
                            ->getFlashBag()
                            ->add('status', 'Settings saved.');
                }
            }
        }

        if ($request->isXmlHttpRequest()) {
            $response = new JsonResponse();
            $response->setData([
                'html' => $this->renderView('KaikmediaGalleryModule:Admin:settings.form.html.twig', [
                    'form' => $form->createView(),
                    'settings' => $settingsManager->getSettings()
                ])
            ]);

            return $response;
        }

        return $this->render('KaikmediaGalleryModule:Admin:settings.html.twig', [
                    'form' => $form->createView(),
                    'settings' => $settingsManager->getSettings()
        ]);
    }

}