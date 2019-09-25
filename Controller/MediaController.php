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
        // Permission check
        $this->get('kaikmedia_gallery_module.access_manager')->hasPermission(ACCESS_OVERVIEW);

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
     *     defaults={"_format": "html", "type": "image"},
     *     requirements={
     *         "_format": "html|json"
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
        $this->get('kaikmedia_gallery_module.access_manager')->hasPermission(ACCESS_READ);
        
        $errors = [];

        $mediaManager = $this->get('kaikmedia_gallery_module.media_manager')->create($type);
        
        $mediaItem = $mediaManager->getMediaItem();

        $formClass = $mediaManager->getName();

        $form = $this->createForm($formClass, $mediaItem, ['isXmlHttpRequest' => $request->isXmlHttpRequest()]);
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            if ($mediaItem->isUploadable()) {
                $mediaItem->setUploadRootDir($this->get('kernel')->getProjectDir().$this->getVar('upload_dir'));
                $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');
                $file = $form->has('file') ? $form->get('file')->getData() : null;
                $uploadableManager->markEntityToUpload($mediaItem, $file);
            }
            
            $em->persist($mediaItem);
            $em->flush();
        }
        
        //json
        if ($_format == 'json') {
            foreach ($form->getErrors(true, true) as $formError) {
               $errors[] = $formError->getMessage();
            }
            $data = [
                'id'            => $mediaItem->getId(),
                'title'         => $mediaItem->getTitle(),
                'mediaExtra'    => $mediaItem->getMediaExtra(),
                'errors'        => $errors,
                'type'          => $type,
                '_format'       => $_format,
            ];
            $response = new JsonResponse($data);

            return $response;
        }

        return $this->render('KaikmediaGalleryModule:Media:create.html.twig', [
            'form' => $form->createView(),
            'media_id'      => $mediaItem->getId(),
            'media_extra'   => $mediaItem->getMediaExtra(),
        ]);
    }

    
        
//        if ($request->getMethod() == "POST") {
//            
//            if ($mediaItem->isUploadable()) {
//                $mediaItem->setFile($request->files->get('file'));
//                $mediaItem->setUploadRootDir($this->get('kernel')->getProjectDir().$this->getVar('upload_dir'));
//                $mediaItem->setPrefix($request->request->get('prefix'));
//                $mediaItem->setDir($request->request->get('dir'));
//            }

        
//        $em = $this->getDoctrine()->getManager();
        
        // Decode de JSON input
//        $mediaItemData = json_decode($request->getContent(), true);
//        $form->submit($mediaItemData);

        // Post the data to the form
    
    
    /**
     * @Route(
     *     "/remove/{_format}",
     *     defaults={"_format": "json"},
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
    public function removeAction(Request $request, $_format)
    {
        // Permission check
        $this->get('kaikmedia_gallery_module.access_manager')->hasPermission(ACCESS_READ);

        $errors = false;
        if ($request->getMethod() == "POST") {

            $mediaRelation = $request->request->get('media_relation', null);
            $mediaItem = $request->request->get('media_item', null);
            $em = $this->getDoctrine()->getManager();

            if (is_numeric($mediaRelation)) {
                $mediaRelationObject = $em
                    ->getRepository('Kaikmedia\GalleryModule\Entity\Relations\HooksRelationsEntity')
                        ->find($mediaRelation);
                if ($mediaRelationObject) {
                    $em->remove($mediaRelationObject);
                    $em->flush();
                }


            }
            if (is_numeric($mediaItem)) {
                $mediaObject = $em->getRepository('Kaikmedia\GalleryModule\Entity\Media\AbstractMediaEntity')->find($mediaItem);
                if ($mediaObject) {
                    $mediaExtra = $mediaObject->getMediaExtra();
                    if (array_key_exists('fileName', $mediaExtra)){
                        $filePathToRemove = $this->get('kernel')->getProjectDir()."/web/uploads/". $mediaExtra['fileName'];
                            if (file_exists($filePathToRemove)) {
                                unlink($filePathToRemove);
                            }
                    }
                    $em->remove($mediaObject);
                    $em->flush();
                }
            }
        }

        //json
        if ($_format == 'json') {
            $data = [
                'errors' => $errors,
                '_format' => $_format,
                'media_relation' => $mediaRelation,
                'media_item' => $mediaItem
            ];

            $response = new JsonResponse($data);

            return $response;
        }

        return $this->render('KaikmediaGalleryModule:Media:remove.html.twig', [
        ]);
    }

    /**
     * @Route(
     *     "/replace/{type}/{_format}",
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
    public function replaceAction(Request $request, $type, $_format)
    {
        // Permission check
        $this->get('kaikmedia_gallery_module.access_manager')->hasPermission(ACCESS_READ);

        $mediaManager = $this->get('kaikmedia_gallery_module.media_manager')->create($type);
        $mediaItem = $mediaManager->getMediaItem();

        $errors = false;
        if ($request->getMethod() == "POST") {
            // remove old first
            $removeMediaRelation = $request->request->get('media_relation', null);
            $removeMediaItem = $request->request->get('media_item', null);
            $em = $this->getDoctrine()->getManager();

            if (is_numeric($removeMediaRelation)) {
                $mediaRelationObject = $em
                    ->getRepository('Kaikmedia\GalleryModule\Entity\Relations\HooksRelationsEntity')
                        ->find($removeMediaRelation);
                if ($mediaRelationObject) {
                    $em->remove($mediaRelationObject);
                    $em->flush();
                }


            }
            if (is_numeric($removeMediaItem)) {
                $mediaObject = $em->getRepository('Kaikmedia\GalleryModule\Entity\Media\AbstractMediaEntity')->find($removeMediaItem);
                if ($mediaObject) {
                    $removeMediaExtra = $mediaObject->getMediaExtra();
                    if (array_key_exists('fileName', $removeMediaExtra)){
                        $filePathToRemove = $this->get('kernel')->getProjectDir()."/web/uploads/". $removeMediaExtra['fileName'];
                            if (file_exists($filePathToRemove)) {
                                unlink($filePathToRemove);
                            }
                    }
                    $em->remove($mediaObject);
                    $em->flush();
                }
            }
            // add new
            if ($mediaItem->isUploadable()) {
                $file = $request->files->get('file');
                // If a file was uploaded
                if(!is_null($file)){
                   // generate a random name for the file but keep the extension
                   $filename = uniqid().".".$file->getClientOriginalExtension();
                   $path = $this->get('kernel')->getProjectDir()."/web/uploads";
                   $file->move($path, $filename);
                   $mediaExtra = [];
                   $mediaExtra['fileName'] = $filename;
                   $mediaExtra['ext'] = $file->getClientOriginalExtension();
                   $mediaItem->setTitle($file->getClientOriginalName());
                   $mediaItem->setMediaExtra($mediaExtra);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($mediaItem);
            $em->flush();
        }

        //json
        if ($_format == 'json') {
            $data = [
                'media' => $mediaItem,
                'media_id' => $mediaItem->getId(),
                'errors' => $errors,
                '_format' => $_format,
            ];

            $response = new JsonResponse($data);

            return $response;
        }

        return $this->render('KaikmediaGalleryModule:Media:replace.html.twig', [
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

        $mediaManager = $this->get('kaikmedia_gallery_module.media_manager')->create('image');
        $mediaItem = $mediaManager->getMediaItem();
        $formClass = $mediaManager->getName();

        $form = $this->createForm($formClass, $mediaItem, ['isXmlHttpRequest' => $request->isXmlHttpRequest()]);

//        dump( $mediaItem );
//        $media = new Media();
//
//        $form = $this->createForm(MediaType::class, $media);
//
//        $form->handleRequest($request);
//        $em = $this->getDoctrine()->getManager();
//        if ($form->isValid()) {
//            $em->persist($media);
//
//            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');
//
//            $file = $form->has('file') ? $form->get('file')->getData() : null;
//            $uploadableManager->markEntityToUpload($media, $file);
//
//            $em->flush();
//            $request->getSession()
//                    ->getFlashBag()
//                    ->add('status', $this->__('Media added!'));
//            return $this->redirect($this->generateUrl('kaikmediagallerymodule_admin_mediastore'));
//        }

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
            $media = $this->getDoctrine()->getManager()->getRepository('Kaikmedia\GalleryModule\Entity\Media\AbstractMediaEntity')->getOneBy([
                'id' => $id
            ]);
        }

        $form = $this->createForm(MediaType::class, $media);

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

            return $this->redirect($this->generateUrl('kaikmediagallerymodule_media_mediastore'));
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

//        $form = $this->createFormBuilder($a)
//                ->add('name', 'text', ['required' => false])
//                ->add('filter', 'submit', ['label' => 'Filter'])
//                ->getForm();

//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
//            $data = $form->getData();
//            $a['limit'] = $data['limit'] ? $data['limit'] : $a['limit'];
//            $a['name'] = $data['name'] ? $data['name'] : $a['name'];
//        }

        // Get parameters from whatever input we need.
        $mediarelations = $this->getDoctrine()->getManager()->getRepository('Kaikmedia\GalleryModule\Entity\Relations\HooksRelationsEntity')->getAll($a);

        return $this->render('KaikmediaGalleryModule:Admin:mediarelations.html.twig', [
                    'mediarelations' => $mediarelations,
                    'settings' => $this->getVars(),
//                    'form' => $form->createView(),
                    'thisPage' => $a['page'],
                    'maxPages' => ceil($mediarelations->count() / $a['limit'])
        ]);
    }

    /**
     * @Route("/mediastore/{page}", requirements={"page" = "\d*"}, defaults={"page" = 1})
     *
     * @Theme("admin")
     *
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

//        $form = $this->createFormBuilder($a)
//                ->add('name', 'text', ['required' => false])
//                ->add('filter', 'submit', ['label' => 'Filter'])
//                ->getForm();
//
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
//            $data = $form->getData();
//            $a['limit'] = $data['limit'] ? $data['limit'] : $a['limit'];
//            $a['name'] = $data['name'] ? $data['name'] : $a['name'];
//        }

        // Get parameters from whatever input we need.
        $media = $this->getDoctrine()->getManager()->getRepository('Kaikmedia\GalleryModule\Entity\Media\AbstractMediaEntity')->getAll($a);

        return $this->render('KaikmediaGalleryModule:Admin:mediastore.html.twig', [
                    'media' => $media,
                    'settings' => $this->getVars(),
//                    'form' => $form->createView(),
                    'thisPage' => $a['page'],
                    'maxPages' => ceil($media->count() / $a['limit'])
        ]);
    }
}