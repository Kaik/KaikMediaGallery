<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Entity;

use ServiceUtil;
use UserUtil;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Media
 * @ORM\Table(name="kmgallery_mediaobjmap")
 * @ORM\Entity(repositoryClass="Kaikmedia\GalleryModule\Entity\Repository\MediaObjMapRepository")
 * 
 */
class MediaObjMapEntity
{

    /**
     * @var integer 
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=60, nullable=false)
     */
    private $obj_name;

    /**
     * @ORM\Column(type="integer", length=60, nullable=false)
     */
    private $obj_id;  
    
    /**
     * @ORM\ManyToOne(targetEntity="Kaikmedia\GalleryModule\Entity\MediaEntity")
     * @ORM\JoinColumn(name="media", referencedColumnName="id")
     */
    protected $media;    

    /**
     * @ORM\ManyToOne(targetEntity="Zikula\Module\UsersModule\Entity\UserEntity")
     * @ORM\JoinColumn(name="author", referencedColumnName="uid")
     */
    private $author;
    
    /**
     * @ORM\Column(type="string", length=150)
     */
    private $status = 'A';
    
    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;
    
    /**
     * The user id of the creator of media obj mapping
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Zikula\Module\UsersModule\Entity\UserEntity")
     * @ORM\JoinColumn(name="createdBy", referencedColumnName="uid")
     */
    private $createdBy;
    
    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;
    
    /**
     * The user id of the last updater of the category
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Zikula\Module\UsersModule\Entity\UserEntity")
     * @ORM\JoinColumn(name="updatedBy", referencedColumnName="uid")
     */
    private $updatedBy;
    
    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="Zikula\Module\UsersModule\Entity\UserEntity")
     * @ORM\JoinColumn(name="deletedBy", referencedColumnName="uid")
     */
    private $deletedBy;
    
    /**
     * constructor
     */
    public function __construct()
    {
        $em = ServiceUtil::getService('doctrine.entitymanager');
        $this->author = $em->getRepository('Zikula\Module\UsersModule\Entity\UserEntity')->findOneBy(array(
            'uid' => UserUtil::getVar('uid')
        ));
    }    
    
    /**
     * Get id
     * 
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get obj_name
     *
     * @return string
     */
    public function getObj_Name()
    {
        return $this->obj_name;
    }

    /**
     * Set obj_name
     *
     * @param string $obj_name            
     */
    public function setObj_Name($obj_name)
    {
        $this->obj_name = $obj_name;
        return $this;
    }

    /**
     * Get obj_id
     *
     * @return integer
     */
    public function getObj_id()
    {
        return $this->obj_id;
    }

    /**
     * Set obj_id
     * 
     * @param integer $obj_id            
     */
    public function setObj_id($obj_id)
    {
        $this->obj_id = $obj_id;
        return $this;
    }

    /**
     * Get media
     * 
     * @return object $media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set media
     *
     * @param object $media            
     */
    public function setMedia($media)
    {
        $this->media = $media;
        return $this;
    }
     
    /**
     * Set author
     * 
     * @param integer $author            
     * @return Pages
     */
    public function setAuthor(\Zikula\Module\UsersModule\Entity\UserEntity $author = null)
    {
        $this->author = $author;        
        return $this;
    }

    /**
     * Get author
     * 
     * @return integer
     */
    public function getAuthor()
    {
        return $this->author;
    }
    
    /**
     * Set obj_status
     *
     * @param string $obj_status
     * @return Pages
     */
    public function setStatus($status)
    {
        $this->status = $status;    
        return $this;
    }
    
    /**
     * Get obj_status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Pages
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;    
        return $this;
    }
    
    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return Pages
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;    
        return $this;
    }
    
    /**
     * Get createdBy
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
    
    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Pages
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = new \DateTime($updatedAt);    
        return $this;
    }
    
    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    /**
     * Set updatedBy
     *
     * @param integer $updatedBy
     * @return Pages
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;    
        return $this;
    }
    
    /**
     * Get updatedBy
     *
     * @return integer
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
    
    /**
     * Get deleted at status
     *
     * @return DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }
    
    /**
     * Set deleted at status
     *
     * @return integer
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }
    
    /**
     * Get deleted by status
     *
     * @return integer
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }
    
    /**
     * Set deleted by
     *
     * @return integer
     */
    public function setDeletedBy($deletedBy)
    {
        $this->deletedBy = $deletedBy;
    }   
}