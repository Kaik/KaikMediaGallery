<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Media;

use Doctrine\ORM\EntityManager;
use Zikula\Common\Translator\TranslatorInterface;
use Kaikmedia\GalleryModule\Settings\SettingsManager;
use Kaikmedia\GalleryModule\Security\AccessManager;
use Kaikmedia\GalleryModule\Collector\MediaHandlersCollector;
use Kaikmedia\GalleryModule\Entity\Media\AbstractMediaEntity;

/**
 * MediaManager
 *
 * @author Kaik
 */
class MediaManager
{
    private $name;
    
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var SettingsManager
     */
    private $settingsManager;

    /**
     * @var AccessManager
     */
    private $accessManager;
   
    /**
     * @var MediaHandlersCollector
     */
    private $mediaHandlersCollector;
    
    
    private $handler;
    
    private $mediaItem;

    /**
     * Construct the manager
     *
     * @param TranslatorInterface $translator
     * @param EntityManager $entityManager
     * @param SettingsManager $settingsManager
     * @param AccessManager $accessManager
     * @param MediaHandlersCollector $mediaHandlersCollector
     */
    public function __construct(
        TranslatorInterface $translator,
        EntityManager $entityManager,
        SettingsManager $settingsManager,
        AccessManager $accessManager,
        MediaHandlersCollector $mediaHandlersCollector
    ) {
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->settingsManager = $settingsManager;
        $this->accessManager = $accessManager;
        $this->mediaHandlersCollector = $mediaHandlersCollector;
    }    
    
    /**
     * Start managing
     *
     * @return Manager
     */
    public function getManager($id = null, $mediaItem = null, $create = true)
    {
        if ($mediaItem instanceof AbstractMediaEntity) {
            // injected
            $this->$mediaItem = $mediaItem;
        } elseif ($id > 0) {
            // existing
            $this->mediaItem = $this->entityManager->find('Kaikmedia\GalleryModule\Entity\Media\AbstractMediaEntity', $id);
        }

        return $this;
    }

    /**
     * Check if topic exists
     *
     * @return bool
     */
    public function exists()
    {
        return $this->mediaItem ? true : false;
    }

    /**
     * Get the Post entity
     *
     * @return PostEntity
     */
    public function get()
    {
        return $this->mediaItem;
    }

    /**
     * Get post id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->mediaItem->getId();
    }

    /**
     * Get post as array
     *
     * @return mixed array or false
     */
    public function toArray()
    {
        if (!$this->mediaItem) {
            return [];
        }

        $post = $this->mediaItem->toArray();

        return $post;
    }
    
    public function create($type) {
        $this->handler = $this->mediaHandlersCollector->get($type);
        if (!$this->handler) {
            return false;
        }
        
        $mediaClass = $this->handler->getEntityClass();
        $this->mediaItem = new $mediaClass();
        
        return $this;  
    }
    
    public function getMediaItem() {
        return $this->mediaItem;  
    }   
    
    public function getName() {
        return $this->handler->getFormClass(); 
    }     
    
    public function getClass() { 
        return $this->handler->getEntityClass();
    }     
    
    public function getForm() { 
        $type = $this->handler->getType();
        $formClass = $this->handler->getFormClass();
        $form = new $formClass($type);
        return $form;    
    }     
}
