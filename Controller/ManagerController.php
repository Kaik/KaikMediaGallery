<?php
/**
 * Copyright (c) KaikMedia.com 2015
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
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/manager")
 */
class ManagerController extends AbstractController
{
   
    /**
     * @Route("/edit", options={"expose"=true})
     * @Method("GET")
     * Modify aplicant information.
     *
     * @param Request $request
     * @param integer $id
     *
     * Parameters passed via GET:
     * --------------------------------------------------
     * string   uname The user name of the account for which profile information should be modified; defaults to the uname of the current user.
     * dynadata array The modified profile information passed into this function in case of an error in the update function.
     *
     * @return RedirectResponse|string The rendered template output.
     *
     * @throws AccessDeniedException on failed permission check
     */
    public function editAction(Request $request)
    {
        // Permission check
        if (!$this->get('kaikmedia_gallery_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }
        
        $original_id = $request->query->get('original', false);
        $relation_id = $request->query->get('relation', 'new');
        //return when original id is not present and relation Id/new is not present
        $mode = $request->query->get('mode', 'info');        
        
        $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');                
        $settings = new Settings();
         
        if ($mode == 'info'){
        	$original = $this->entityManager
        	->getRepository('Kaikmedia\GalleryModule\Entity\MediaEntity')
        	->find($original_id);
        	//check if exist
        	//error if not found
        	
        	$options['isXmlHttpRequest'] = $request->isXmlHttpRequest();
        	$original_form = $this->createForm('media', $original, $options);
        	
        	$template = $this->renderView('KaikmediaGalleryModule:Media:item.modify.html.twig', array(
        			'form'=> $original_form->createView(),
        			'relation' => false,
        			'original' => $original,
        			'settings' => ModUtil::getVar($this->name)
        	));	         	      	
        }else{   	
        	if($relation_id == 'new'){
        		$original = $this->entityManager
        		->getRepository('Kaikmedia\GalleryModule\Entity\MediaEntity')
        		->find($original_id);
        		if($original){
        			$relation = new MediaRelation();
        			$relation->setOriginal($original);
        			$relation->setType($mode);
        			
        		}else{
        		//original not found error	
        		}        		
        	}elseif(is_numeric($relation_id)) {
        		$relation = $this->entityManager
        		->getRepository('Kaikmedia\GalleryModule\Entity\MediaRelationsEntity')
        		->find($relation_id);        		
        	}else{
        		//relation coruppted
        	}
        
        	//error if not found
        	 
        	$options['isXmlHttpRequest'] = $request->isXmlHttpRequest();
        	$relation_form = $this->createForm('media_relation', $relation, $options);
        	 
        	$template = $this->renderView('KaikmediaGalleryModule:Features:'. $mode . '.feature.modify.html.twig', array(
        			'form' => $relation_form->createView(),
        			'relation' => $relation,
        			'original' => $relation->getOriginal(),
        			'previews' => $settings->getPreviewsSelect(),
        			'settings' => ModUtil::getVar($this->name)
        	)); 	
        }
         
        $response = new Response(json_encode(array('template' => $template)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    } 

    public function postInitialize()
    {
        $this->view->setCaching(false);
    }

    /**
     * Create and configure the view for the controller.
     *
     * NOTE: This is necessary because the Zikula_Controller_AbstractAjax overrides this method located in Zikula_AbstractController.
     */
    protected function configureView()
    {
        $this->setView();
        $this->view->setController($this);
        $this->view->assign('controller', $this);
    }
}