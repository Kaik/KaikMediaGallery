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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Kaikmedia\GalleryModule\Form\Features\AddMediaType;

/**
 */
class PluginController extends AbstractController
{
    /**
     * This function generate
     *
     * @return RedirectResponse
     */
    public function managerAction(Request $request, $obj_reference = null, $mode = 'info')
    {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $gallerySettings = ['mode' => $mode,
            'obj_reference' => $obj_reference];

        $masterRequest = $this->get('request_stack')->getMasterRequest();
        $gallerySettings['obj_name'] = $masterRequest->attributes->get('_zkModule');
        /*
          $addMediaForm = $this->createForm(
          new AddMediaType(), null , ['allowed_mime_types' => $this->get('kaikmedia_gallery_module.settings_manager')->getAllowedMimeTypesForObject($gallerySettings['obj_name']),
          'isXmlHttpRequest' => $request->isXmlHttpRequest()]

          );
         */
        //$gallerySettings['mediaTypes'] = $this->get('kaikmedia_gallery_module.media_handlers_manager')->getSupportedMimeTypes();
        $gallerySettings['settings'] = $this->get('kaikmedia_gallery_module.settings_manager')->getSettingsArray();

//        \PageUtil::addVar('javascript', "@KaikmediaGalleryModule/Resources/public/js/Kaikmedia.Gallery.settings.js");
//        \PageUtil::addVar('javascript', "@KaikmediaGalleryModule/Resources/public/js/Kaikmedia.Gallery.mediaItem.js");
//        \PageUtil::addVar('javascript', "@KaikmediaGalleryModule/Resources/public/js/Kaikmedia.Gallery.Manager.js");
//        \PageUtil::addVar('stylesheet', "@KaikmediaGalleryModule/Resources/public/css/gallery.manager.css");
//        \PageUtil::addVar('stylesheet', "@KaikmediaGalleryModule/Resources/public/css/gallery.mediaItem.css");

        return $this->render('KaikmediaGalleryModule:Plugin:manager.html.twig', [
                    'gallerySettings' => $gallerySettings,
                        //   'addMediaForm' => $addMediaForm->createView()
        ]);
    }
}
/*
        $newRelationForm = false;

        //get mediarelations assigned to calling object or create new object form


        if ($obj_reference == null) {

            $icon = false;
            $featured = false;
            $additional = false;
            $insert = false;
        } else {

            $icon = $this->get('doctrine.entitymanager')
                    ->getRepository('Kaikmedia\GalleryModule\Entity\MediaRelationsEntity')
                    ->getOneBy(array('obj_name' => $obj_name,
                'type' => 'icon',
                'obj_reference' => $obj_reference));


            $featured = $this->get('doctrine.entitymanager')
                    ->getRepository('Kaikmedia\GalleryModule\Entity\MediaRelationsEntity')
                    ->getOneBy(array('obj_name' => $obj_name,
                'type' => 'featured',
                'obj_reference' => $obj_reference));


            $additional = $this->get('doctrine.entitymanager')
                    ->getRepository('Kaikmedia\GalleryModule\Entity\MediaRelationsEntity')
                    ->getAll(array('obj_name' => $obj_name,
                'type' => 'additional',
                'obj_reference' => $obj_reference));

            $insert = $this->get('doctrine.entitymanager')
                    ->getRepository('Kaikmedia\GalleryModule\Entity\MediaRelationsEntity')
                    ->getAll(array('obj_name' => $obj_name,
                'type' => 'insert',
                'obj_reference' => $obj_reference));
        }

        */