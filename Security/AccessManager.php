<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Common\Translator\TranslatorInterface;
//use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\PermissionsModule\Api\PermissionApi;

/**
 * AccessManager.
 *
 * @author Kaik
 */
class AccessManager
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var PermissionApi
     */
    private $permissionApi;

    /**
     * @var CurrentUser
    */
    private $user;

    public function __construct(
        RequestStack $requestStack,
        TranslatorInterface $translator,
        PermissionApi $permissionApi
    ) {
        $this->name = 'KaikmediaGalleryModule';
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getMasterRequest();
        $this->translator = $translator;
        $this->permissionApi = $permissionApi;
        $this->user = $this->request->getSession()->get('uid') > 1 ? $this->request->getSession()->get('uid') : 1;
    }

    /*
     * Do all user checks in one method:
     * Check if logged in, has correct access, and if site is disabled
     * Returns the appropriate error/return value if failed, which can be
     *          returned by calling method.
     * Returns false if use has permissions.
     * On exit, $uid has the user's UID if logged in.
     */
    public function hasPermission($level = ACCESS_READ, $throw = true , $component = '', $instance = '')
    {
        // If not logged in, redirect to login screen
        if ($this->user <= 1 && $throw) {
            throw new AccessDeniedException();
        } else {
            $allowed = false;
        }

        // @todo module enabled/disabled check

        // Zikula perms check
        if (!$this->hasPermissionRaw($component, $instance, $level) && $throw) {
            throw new AccessDeniedException();
        } else {
            $allowed = false;
        }

        // Return user uid to signify everything is OK.
        return $allowed;
    }

    public function hasPermissionRaw($component, $instance, $level)
    {
        return $this->permissionApi->hasPermission($this->name.'::', $component.'::'.$instance, $level);
    }
}
