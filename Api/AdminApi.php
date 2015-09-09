<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Api;

use System;
use SecurityUtil;
use ModUtil;
use Kaikmedia\GalleryModule\Entity\MediaEntity;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * API functions used by administrative controllers
 */
class AdminApi extends \Zikula_AbstractApi
{

    /**
     * get available admin panel links
     * 
     * @return array array of admin links
     */
    public function getLinks()
    {
        $links = array();
        if (SecurityUtil::checkPermission('KaikmediaGalleryModule::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => $this->get('router')->generate('kaikmediagallerymodule_admin_info'),'text' => $this->__('Info'),'title' => $this->__('Here you can view gallery informations and statistics'),'icon' => 'info-circle'
            );
            $links[] = array(
                'url' => $this->get('router')->generate('kaikmediagallerymodule_admin_albums'),'text' => $this->__('Albums'),'title' => $this->__('Here you can view gallery album tree'),'icon' => 'folder'
            );            
            $links[] = array(
                'url' => $this->get('router')->generate('kaikmediagallerymodule_admin_mediaobjmap'),'text' => $this->__('Media to objects map'),'title' => $this->__('Media object map manager'),'icon' => 'share-alt '
            );
            $links[] = array(
                'url' => $this->get('router')->generate('kaikmediagallerymodule_admin_mediastore'),'text' => $this->__('Media store'),'title' => $this->__('Media store manager'),'icon' => 'hdd-o'
            );
            $links[] = array(
                'url' => $this->get('router')->generate('kaikmediagallerymodule_admin_addnew'),'text' => $this->__('Add media'),'title' => $this->__('Add media'),'icon' => 'plus'
            );
            $links[] = array(
                'url' => $this->get('router')->generate('kaikmediagallerymodule_admin_preferences'),'text' => $this->__('Settings'),'title' => $this->__('Adjust module settings'),'icon' => 'wrench'
            );
        }
        return $links;
    }
}