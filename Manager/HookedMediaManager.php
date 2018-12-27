<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Manager;

use Doctrine\ORM\EntityManager;
//use Doctrine\Common\Collections\AbstractLazyCollection;
use Zikula\Common\Translator\TranslatorInterface;
use Kaikmedia\GalleryModule\Settings\SettingsManager;

/**
 * Post manager
 */
class HookedMediaManager
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Settings
     */
    private $settings;

    /**
     * @var string
     */
    private $hookedModule;

    /**
     * @var string
     */
    private $hookedAreaId;

    /**
     * @var string
     */
    private $hookedObjectId;

    /**
     * @var string
     */
    private $hookedUrlObject;

    private $hookedMedia;

    public function __construct(
        TranslatorInterface $translator,
        EntityManager $entityManager,
        SettingsManager $settings
    ) {
        $this->name = 'KaikmediaNewsModule';
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->settings = $settings;

    }

    /**
     * Start managing by hook
     *
     * @param Hook $hook
     * @param boolean $create
     *
     * @return TopicManager
     */
    public function getManager($hookedModule = null, $hookedAreaId = null, $hookedObjectId = null, $feature = null)
    {
        $this->hookedModule     = $hookedModule;
        $this->hookedAreaId     = $hookedAreaId;
        $this->hookedObjectId   = $hookedObjectId;

        $hookedMedia = $this->entityManager
            ->getRepository('Kaikmedia\GalleryModule\Entity\Relations\HooksRelationsEntity')
                ->findOneBy(['feature' => $feature, 'hookedModule' => $module, 'hookedObjectId' => $objId]);

//        $this->hookedModuleService = $this->topicHookedModuleCollector->get($this->hookedModule);

//        $hookedMedia = $this->entityManager->getRepository('Zikula\DizkusModule\Entity\TopicEntity')->getHookedTopic($hookedModule, $hookedAreaId, $hookedObjectId);

//        if ($topic) {
//            // topic has been injected
//            $this->_topic = $topic;
//        } elseif ($create) {
//            // create new topic
//            $this->create();
//        }

        return $this;
    }

    /**
     * Check if topic exists
     *
     * @return bool
     */
    public function exists()
    {
        return $this->_item ? true : false;
    }

    /**
     * Get the Post entity
     *
     * @return PostEntity
     */
    public function get()
    {
        return $this->_item;
    }

    /**
     * Get post id
     *
     * @return integer
     */
    public function getSlug()
    {
        return $this->_item->getUrltitle();
    }

    /**
     * Get post id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_item->getId();
    }

    /**
     * Get post as array
     *
     * @return mixed array or false
     */
    public function toArray()
    {
        if (!$this->_item) {
            return [];
        }

        $post = $this->_item->toArray();

        return $post;
    }

    /**
     * Create a post from provided data but do not yet persist
     *
     * @todo Add create validation
     * @todo event
     *
     * @return bool
     */
    public function create($data = null)
    {
//        if (!is_null($data)) {
//            $this->_topic = $this->topicManagerService->getManager($data['topic_id']);
//            $this->_item->setTopic($this->_topic->get());
//            unset($data['topic_id']);
//            $this->_item->merge($data);
//        } else {
//            throw new \InvalidArgumentException($this->translator->__('Cannot create Post, no data provided.'));
//        }
//        $managedForumUser = $this->forumUserManagerService->getManager();
//        $this->_item->setPoster($managedForumUser->get());

        return $this;
    }

    /**
     * Update post
     *
     * @param array/object $data Post data or post object to save
     *
     * @return bool
     */
    public function update($data = null)
    {
        if (is_null($data)) {
            throw new \InvalidArgumentException($this->translator->__('Cannot create Post, no data provided.'));
        } elseif ($data instanceof NewsEntity) {
            $this->_item = $data;
        } elseif (is_array($data)) {
            $this->_item->merge($data);
        }

        return $this;
    }

    /**
     * Persist the post and update related entities to reflect new post
     *
     * @todo Add validation ?
     * @todo event
     *
     * @return $this
     */
    public function store()
    {
        $this->entityManager->persist($this->_item);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * Delete post
     *
     * @return $this
     */
    public function delete()
    {
        // preserve post_id
        $itemArray = $this->toArray();
        // remove the post
        $this->entityManager->remove($this->_item);
        $this->entityManager->flush();

        return $itemArray;
    }
}
