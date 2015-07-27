<?php
/**
 * Copyright (c) KaikMedia.com 2014
 */
namespace Kaikmedia\GalleryModule\Hook;

use ServiceUtil;
use SecurityUtil;
use ModUtil;
use PageUtil;
use System;
use ZLanguage;
use Zikula_View;
use Zikula\Core\Hook\AbstractHookListener;
use Zikula\Core\Hook\DisplayHook;
use Zikula\Core\Hook\ProcessHook;
use Zikula\Core\Hook\DisplayHookResponse;
use Zikula\Core\Hook\ValidationHook;

class MediaHandlers extends AbstractHookListener
{

    /**
     * Zikula_View instance
     * @var Zikula_View
     */
    private $view;

    /**
     * Zikula entity manager instance
     * @var \Doctrine\ORM\EntityManager
     */
    private $_em;

    /**
     * Module name
     * @var string
     */
    const MODULENAME = 'KaikmediaGalleryModule';

    /**
     * Post constructor hook.
     *
     * @return void
     */
    public function setup()
    {
        $this->view = Zikula_View::getInstance(self::MODULENAME, false);
        // set caching off
        $this->_em = ServiceUtil::get('doctrine.entitymanager');
        $this->domain = ZLanguage::getModuleDomain(self::MODULENAME);
    }

    /**
     * Display hook for view.
     *
     * @param DisplayHook $hook The hook.
     *
     * @return string
     */
    public function uiView(DisplayHook $hook)
    {
        
        // first check if the user is allowed to do any comments for this module/objectid
        if (!SecurityUtil::checkPermission("{$hook->getCaller()}", '::', ACCESS_READ)) {
            return;
        }

        //$request = $this->view->getRequest();
        //
        $a = array('obj_name' => $hook->getCaller(),
                   'obj_id' => $hook->getId()
        );
        
        $media = $this->_em->getRepository('Kaikmedia\GalleryModule\Entity\MediaObjMapEntity')->getOneBy($a);
        if (isset($media)) {
          
        } else {
            return;
        }
        
        /*
        // attempt to retrieve return url from hook or create if not available
        $url = $hook->getUrl();
        if (isset($url)) {
            $urlParams = $url->toArray();
        } else {
            $urlParams = $request->query->all();
            $route = $request->get('_route');
            if (isset($route)) {
                $urlParams['route'] = $route;
            }
        }
        $returnUrl = htmlspecialchars(json_encode($urlParams));
        $this->view->assign('returnUrl', $returnUrl);
        list(, $ranks) = ModUtil::apiFunc(self::MODULENAME, 'Rank', 'getAll', array('ranktype' => RankEntity::TYPE_POSTCOUNT));
        $this->view->assign('ranks', $ranks);
        $this->view->assign('start', $start);

        $this->view->assign('posts', $managedTopic->getPosts(--$start));
        $this->view->assign('pager', $managedTopic->getPager());
        $this->view->assign('permissions', $managedTopic->getPermissions());
        $this->view->assign('breadcrumbs', $managedTopic->getBreadcrumbs());
        $this->view->assign('isSubscribed', $managedTopic->isSubscribed());
        $this->view->assign('nextTopic', $managedTopic->getNext());
        $this->view->assign('previousTopic', $managedTopic->getPrevious());
        //$this->view->assign('last_visit', $last_visit);
        //$this->view->assign('last_visit_unix', $last_visit_unix);
        $managedTopic->incrementViewsCount();
        PageUtil::addVar('stylesheet', "@ZikulaDizkusModule/Resources/public/css/style.css");
        */ 
             
        $this->view->assign('media', $media->toArray());
        $this->view->assign('settings', ModUtil::getVar(self::MODULENAME));
        $hook->setResponse(new DisplayHookResponse('provider.kaikmediagallery.ui_hooks.media', $this->view, 'Hook/display_media.tpl'));
    }  
}