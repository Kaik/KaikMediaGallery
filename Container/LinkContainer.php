<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Container;

use Symfony\Component\Routing\RouterInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Core\LinkContainer\LinkContainerInterface;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;

class LinkContainer implements LinkContainerInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PermissionApiInterface
     */
    private $permissionApi;

    /**
     * @var bool
     */
    private $enableCategorization;

    /**
     * LinkContainer constructor.
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     * @param PermissionApiInterface $permissionApi
     */
    public function __construct(
        TranslatorInterface $translator,
        RouterInterface $router,
        PermissionApiInterface $permissionApi,
        $enableCategorization
    ) {
        $this->translator = $translator;
        $this->router = $router;
        $this->permissionApi = $permissionApi;
        $this->enableCategorization = $enableCategorization;
    }

    /**
     * get Links of any type for this extension
     * required by the interface
     *
     * @param string $type
     * @return array
     */
    public function getLinks($type = LinkContainerInterface::TYPE_ADMIN)
    {
        $method = 'get' . ucfirst(strtolower($type));
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return [];
    }

    /**
     * get the Admin links for this extension
     *
     * @return array
     */
    private function getAdmin()
    {
        $links = [];
        if ($this->permissionApi->hasPermission('KaikmediaGalleryModule::', '::', ACCESS_ADMIN)) {
            $links[] = [
                'url' => $this->router->generate('kaikmediagallerymodule_admin_info'),
                'text' => $this->translator->__('Info'),
                'title' => $this->translator->__('Here you can view gallery informations and statistics'),
                'icon' => 'dashboard'];
            $links[] = [
                'url' => $this->router->generate('kaikmediagallerymodule_admin_preferences'),
                'text' => $this->translator->__('General settings'),
                'title' => $this->translator->__('Adjust module settings'),
                'icon' => 'magic'];
            $links[] = [
                'url' => $this->router->generate('kaikmediagallerymodule_albums_albums'),
                'text' => $this->translator->__('Albums'),
                'title' => $this->translator->__('Here you can view gallery album tree'),
                'icon' => 'wrench'];
            $links[] = [
                'url' => $this->router->generate('kaikmediagallerymodule_media_mediarelations'),
                'text' => $this->translator->__('Media relations'),
                'title' => $this->translator->__('Media object map manager'),
                'icon' => 'dashboard'];
            $links[] = [
                'url' => $this->router->generate('kaikmediagallerymodule_media_mediastore'),
                'text' => $this->translator->__('Media'),
                'title' => $this->translator->__('Media store manager'),
                'icon' => 'magic'];
            $links[] = [
                'url' => $this->router->generate('kaikmediagallerymodule_media_addnew'),
                'text' => $this->translator->__('Add media'),
                'title' => $this->translator->__('Add media'),
                'icon' => 'wrench'];
        }

        return $links;
    }

    /**
     * get the User Links for this extension
     *
     * @return array
     */
    private function getUser()
    {
        $links = [];
        if ($this->permissionApi->hasPermission('KaikmediaGalleryModule::', '::', ACCESS_OVERVIEW)) {
            $links[] = [
                'url' => $this->router->generate('kaikmediagallerymodule_user_index'),
                'text' => $this->translator->__('Gallery'),
                'title' => $this->translator->__('Manage your media'),
                'icon' => 'image'
            ];
        }

        return $links;
    }

    public function getBundleName()
    {
        return 'KaikmediaGalleryModule';
    }
}
