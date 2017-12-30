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
use Kaikmedia\GalleryModule\Entity\Media\ImageEntity as Media;
use Kaikmedia\GalleryModule\Form\Media\MediaType;
use Kaikmedia\GalleryModule\Entity\AlbumEntity as Album;
use Zikula\ThemeModule\Engine\Annotation\Theme;

/**
 * @Route("/media")
 */
class MediaController extends AbstractController
{
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
    public function getAction(Request $request, $_format, $urltitle)
    {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        //json
        if ($_format == 'json') {
            $data = [
                'urltitle' => $urltitle,
                '_format' => $_format
            ];

            $response = new JsonResponse($data);

            return $response;
        }

        return $this->render('KaikmediaGalleryModule:Media:get.html.twig', [
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
    public function createAction(Request $request, $type, $_format)
    {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $mediaManager = $this->get('kaikmedia_gallery_module.media_manager')->create($type);
        $mediaItem = $mediaManager->getMediaItem();
        $formClass = $mediaManager->getForm();

        $form = $this->createForm($formClass, $mediaItem, ['isXmlHttpRequest' => $request->isXmlHttpRequest()]);

        $errors = false;
        if ($request->getMethod() == "POST") {
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

        return $this->render('KaikmediaGalleryModule:Media:create.html.twig', [
        ]);
    }

    /**
     * @Route("/media/addnew")
     *
     * Modify site information.
     *
     * @param Request $request
     *
     */
    public function addnewAction(Request $request)
    {
        // Permission check
        $this->get('kaikmedia_gallery_module.access_manager')->hasPermission(ACCESS_ADMIN);

        $media = new Media();

        $form = $this->createForm(MediaType::class, $media);

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
                    ->add('status', $this->__('Media added!'));
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
        $this->get('kaikmedia_gallery_module.access_manager')->hasPermission(ACCESS_ADMIN);

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
        $this->get('kaikmedia_gallery_module.access_manager')->hasPermission(ACCESS_ADMIN);

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
        $this->get('kaikmedia_gallery_module.access_manager')->hasPermission(ACCESS_ADMIN);

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
}