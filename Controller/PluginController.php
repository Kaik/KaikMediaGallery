<?php

/**
 * Copyright (c) KaikMedia.com 2015
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
class PluginController extends AbstractController {

    /**
     * This function generate 
     *
     * @return RedirectResponse
     */
    public function managerAction(Request $request, $obj_reference = null, $mode = 'info') {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }
        
        $masterRequest = $this->get('request_stack')->getMasterRequest();
        $obj_name = $masterRequest->attributes->get('_zkModule');
        
        $addMediaForm = $this->createForm(
                new AddMediaType(), null , ['allowed_mime_types' => $this->get('kaikmedia_gallery_module.settings_manager')->getAllowedMimeTypesForObject($obj_name),
                                      'isXmlHttpRequest' => $request->isXmlHttpRequest()]
                
        );

        $media = $this->get('doctrine.entitymanager')
                ->getRepository('Kaikmedia\GalleryModule\Entity\Media\AbstractMediaEntity')
                ->getAll(array('publicdomain' => 'include', 'author' => \UserUtil::getVar('uid')));
        
        \PageUtil::addVar('javascript', "@KaikmediaGalleryModule/Resources/public/js/Kaikmedia.Gallery.settings.js");  
        //\PageUtil::addVar('javascript', "@KaikmediaGalleryModule/Resources/public/js/Kaikmedia.Gallery.SettingsManager.js");         
       // \PageUtil::addVar('javascript', "@KaikmediaGalleryModule/Resources/public/js/Kaikmedia.Gallery.Plugin.js"); 
        \PageUtil::addVar('javascript', "@KaikmediaGalleryModule/Resources/public/js/Kaikmedia.Gallery.mediaItem.js");        
        \PageUtil::addVar('javascript', "@KaikmediaGalleryModule/Resources/public/js/Kaikmedia.Gallery.Manager.js");
        \PageUtil::addVar('stylesheet', "@KaikmediaGalleryModule/Resources/public/css/gallery.manager.css");

        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('KaikmediaGalleryModule:Plugin:manager.html.twig', array(
                    'media' => $media,
                    'mediaTypes' => $this->get('kaikmedia_gallery_module.media_handlers_manager')->getSupportedMimeTypes(),
                    'objects' => $this->get('kaikmedia_gallery_module.settings_manager')->getObjects(),
                    'mode' => $mode,
                    'addMediaForm' => $addMediaForm->createView(),
                    'obj_name' => $obj_name,
                    'obj_reference' => $obj_reference,
                    'settings' => $this->get('kaikmedia_gallery_module.settings_manager')->getSettings(),
        ));
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