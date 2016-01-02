<?php

/**
 * Copyright 
 */

namespace Kaikmedia\GalleryModule\Container;

use Symfony\Component\Routing\RouterInterface;
use Zikula\Common\Translator\Translator;
use Zikula\Core\LinkContainer\LinkContainerInterface;

class LinkContainer implements LinkContainerInterface {

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct($translator, RouterInterface $router) {
        $this->translator = $translator;
        $this->router = $router;
    }

    /**
     * get Links of any type for this extension
     * required by the interface
     *
     * @param string $type
     * @return array
     */
    public function getLinks($type = LinkContainerInterface::TYPE_ADMIN) {
        $method = 'get' . ucfirst(strtolower($type));
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return array();
    }

    /**
     * get the Admin links for this extension
     *
     * @return array
     */
    private function getAdmin() {
        $links = array();
        if (\SecurityUtil::checkPermission('KaikmediaGalleryModule::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => $this->router->generate('kaikmediagallerymodule_admin_info'),
                'text' => $this->translator->__('Info'),
                'title' => $this->translator->__('Here you can view gallery informations and statistics'),
                'icon' => 'dashboard');
            $links[] = array(
                'url' => $this->router->generate('kaikmediagallerymodule_admin_preferences'),
                'text' => $this->translator->__('General settings'),
                'title' => $this->translator->__('Adjust module settings'),
                'icon' => 'magic');
            $links[] = array(
                'url' => $this->router->generate('kaikmediagallerymodule_admin_albums'),
                'text' => $this->translator->__('Albums'),
                'title' => $this->translator->__('Here you can view gallery album tree'),
                'icon' => 'wrench');
            $links[] = array(
                'url' => $this->router->generate('kaikmediagallerymodule_admin_mediarelations'),
                'text' => $this->translator->__('Media relations'),
                'title' => $this->translator->__('Media object map manager'),
                'icon' => 'dashboard');
            $links[] = array(
                'url' => $this->router->generate('kaikmediagallerymodule_admin_mediastore'),
                'text' => $this->translator->__('Media'),
                'title' => $this->translator->__('Media store manager'),
                'icon' => 'magic');
            $links[] = array(
                'url' => $this->router->generate('kaikmediagallerymodule_admin_addnew'),
                'text' => $this->translator->__('Add media'),
                'title' => $this->translator->__('Add media'),
                'icon' => 'wrench');
        }
        return $links;
    }

    /**
     * get the User Links for this extension
     *
     * @return array
     */
    private function getUser() {
        $links = array();
        if (\UserUtil::isLoggedIn()) {
            $links[] = array(
                'url' => $this->router->generate('kaikmediagallerymodule_user_index'),
                'text' => $this->translator->__('Gallery'),
                'title' => $this->translator->__('Manage your media'),
                'icon' => 'image'
            );
        }
        return $links;
    }

    public function getBundleName() {
        return 'KaikmediaGalleryModule';
    }

}
