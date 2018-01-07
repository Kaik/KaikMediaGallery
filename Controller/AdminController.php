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

use Kaikmedia\GalleryModule\Form\Type\Settings\SettingsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Zikula\Core\Controller\AbstractController;
use Zikula\ThemeModule\Engine\Annotation\Theme;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/index")
     *
     * @Theme("admin")
     *
     * @return RedirectResponse
     */
    public function indexAction()
    {
        // Permission check
        $this->get('kaikmedia_gallery_module.access_manager')->hasPermission(ACCESS_ADMIN);

        return new RedirectResponse($this->get('router')->generate('kaikmediagallerymodule_admin_info', [], RouterInterface::ABSOLUTE_URL));
    }

    /**
     * @Route("/info")
     *
     * @Theme("admin")
     *
     * the main administration function
     *
     * @return RedirectResponse
     */
    public function infoAction()
    {
        // Permission check
        $this->get('kaikmedia_gallery_module.access_manager')->hasPermission(ACCESS_ADMIN);

        return $this->render('@KaikmediaGalleryModule/Admin/info.html.twig', [
        ]);
    }

    /**
     * @Route("/preferences")
     *
     * @Theme("admin")
     *
     * @return Response symfony response object
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function preferencesAction(Request $request)
    {
        // Permission check
        $this->get('kaikmedia_gallery_module.access_manager')->hasPermission(ACCESS_ADMIN);

        $settingsManager = $this->get('kaikmedia_gallery_module.settings_manager');
        $project_dir = $this->get('kernel')->getProjectDir();
        $upload_dir = $this->getVar('upload_dir');
        $full_upload_path = $project_dir.$upload_dir;
        $is_writeable = is_writeable($full_upload_path);

        $form = $this->createForm(SettingsType::class, $settingsManager->getSettingsForForm(), ['settingsManager' => $settingsManager]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                if (!$settingsManager->setSettings($request->request->get($form->getName()))) {
                    $this->addFlash('error', $this->__('Error! Settings not set! Please try again'));
                } else {
                    $this->addFlash('status', $this->__('Settings set.'));
                    if (!$settingsManager->saveSettings()) {
                        $this->addFlash('error', $this->__('Error! Settings not saved! Please try again'));
                    } else {
                        $this->addFlash('status', $this->__('Settings saved.'));
                    }
                }
            }
            if ($form->get('restore')->isClicked()) {
                if (!$settingsManager->restoreSettings()) {
                    $this->addFlash('error', $this->__('Error! Settings not set! Please try again'));
                } else {
                    $this->addFlash('error', $this->__('Error! Settings not restored! Please try again'));
                }
            }

            return $this->redirect($this->generateUrl('kaikmediagallerymodule_admin_preferences'));
        }

        return $this->render('@KaikmediaGalleryModule/Admin/settings.html.twig', [
            'project_dir' => $full_upload_path,
            'is_writeable' => $is_writeable,
            'form' => $form->createView(),
        ]);
    }
}
