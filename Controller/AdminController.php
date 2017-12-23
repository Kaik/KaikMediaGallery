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

use Kaikmedia\GalleryModule\Form\Settings\SettingsType;
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

        $a = [];
        // Get startnum and perpage parameter for pager
        $a['page'] = $page;
        $a['limit'] = $request->query->get('limit', 15);
        $a['name'] = $request->query->get('name', false);

        return $this->render('@KaikmediaGalleryModule/Admin/info.html.twig', [
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
        $this->get('kaikmedia_gallery_module.access_manager')->hasPermission(ACCESS_ADMIN);

        $settingsManager = $this->get('kaikmedia_gallery_module.settings_manager');

        $form = $this->createForm(new SettingsType(),
            $settingsManager->getSettingsForForm(),
            ['isXmlHttpRequest' => $request->isXmlHttpRequest()]
        );

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