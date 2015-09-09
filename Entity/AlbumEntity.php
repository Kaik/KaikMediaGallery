<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Entity;

use ServiceUtil;
use ModUtil;
use UserUtil;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Media
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="categories")
 * use repository for handy tree functions
 * @ORM\Table(name="kmgallery_albums")
 * @ORM\Entity(repositoryClass="Kaikmedia\GalleryModule\Entity\Repository\AlbumRepository")
 */
class AlbumEntity
{

        /**
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue
         */
        private $id;
    
        /**
         * @ORM\Column(name="title", type="string", length=64)
         */
        private $title;
    
        /**
         * @Gedmo\TreeLeft
         * @ORM\Column(name="lft", type="integer")
         */
        private $lft;
    
        /**
         * @Gedmo\TreeLevel
         * @ORM\Column(name="lvl", type="integer")
         */
        private $lvl;
    
        /**
         * @Gedmo\TreeRight
         * @ORM\Column(name="rgt", type="integer")
         */
        private $rgt;
    
        /**
         * @Gedmo\TreeRoot
         * @ORM\Column(name="root", type="integer", nullable=true)
         */
        private $root;
    
        /**
         * @Gedmo\TreeParent
         * @ORM\ManyToOne(targetEntity="AlbumEntity", inversedBy="children")
         * @ORM\JoinColumn(name="parent", referencedColumnName="id", onDelete="CASCADE")
         */
        private $parent;
    
        /**
         * @ORM\OneToMany(targetEntity="AlbumEntity", mappedBy="parent")
         * @ORM\OrderBy({"lft" = "ASC"})
         */
        private $children;
        
        /**
         * @ORM\Column(type="text", length=255)
         */
        private $description;
        
        /**
         * The author uid
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
         * The user id of the creator of the category
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
         * Set title
         *
         * @return string
         */        
        public function setTitle($title)
        {
            $this->title = $title;
        }

        /**
         * Get title
         *
         * @return string
         */        
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * Set parent
         *
         * @return string
         */        
        public function setParent(AlbumEntity $parent = null)
        {
            $this->parent = $parent;
        }

        /**
         * Get parent
         *
         * @return string
         */        
        public function getParent()
        {
            return $this->parent;
        }        
    
        /**
         * Get description
         * 
         * @return string
         */
        public function getDescription()
        {
            return $this->description;
        }
    
        /**
         * Set description
         * 
         * @return $this
         */
        public function setDescription($description)
        {
            $this->description = $description;
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
         * Get delete status
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
         * Get deleted by
         *
         * @return integer
         */
        public function setDeletedBy($deletedBy)
        {
            $this->deletedBy = $deletedBy;
        }
        
        /**
         * Get js tree array
         *
         * @return integer
         */
        public function getAsJsTreeArray()
        {
            $parent = $this->parent == null ? '#' : $this->parent->getId();
            $arrayTree = array('id' => $this->id,
                               'text' => $this->title,
                               'parent' => $parent
            );
            
            return $arrayTree;
        } 
        
        public function getMedia()
        {
            $em = ServiceUtil::getService('doctrine.entitymanager');
            $thisMedia = $em->getRepository('Kaikmedia\GalleryModule\Entity\MediaObjMapEntity')
            ->getAll(array('obj_name'=> 'KaikmediaGalleryModule',
                'obj_id' => $this->id
            ));
            
            return $thisMedia;
            
        }
        
        public function __toString()
        {
            return $this->title;
        }     
}