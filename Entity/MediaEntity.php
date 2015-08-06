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
 * @ORM\Table(name="kmgallery_media")
 * @ORM\Entity(repositoryClass="Kaikmedia\GalleryModule\Entity\Repository\MediaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MediaEntity
{

    /**
     * @var integer @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;
    
    /**
     * @ORM\Column(type="string")
     */
    private $size;
    
    /**
     * @ORM\Column(type="integer", length=15)
     */
    private $ext;    

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mimeType;
    
    /**
     *
     * @var string @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $legal;

    /**
     * @ORM\Column(type="boolean")
     */
    private $publicdomain;

    /**
     * The author uid
     * @ORM\ManyToOne(targetEntity="Zikula\Module\UsersModule\Entity\UserEntity")
     * @ORM\JoinColumn(name="author", referencedColumnName="uid")
     */
    private $author;

    /**
     *
     * @var string @ORM\Column(name="path", type="string", length=255)
     */
    private $path;
    
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
     * @ORM\OneToMany(targetEntity="Kaikmedia\GalleryModule\Entity\MediaObjMapEntity", mappedBy="media")
     */
    protected $mappedobjects;    
    
    /**
     * constructor
     */
    public function __construct()
    {
        $em = ServiceUtil::getService('doctrine.entitymanager');
        $this->author = $em->getRepository('Zikula\Module\UsersModule\Entity\UserEntity')->findOneBy(array(
            'uid' => UserUtil::getVar('uid')
        ));
        $this->publicdomian = 0;       
        $this->mappedobjects = new ArrayCollection();
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
     *
     * @return the unknown_type
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     *
     * @param unknown_type $size            
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }
     
    /**
     * Set ext
     *
     * @param string $ext
     * @return Image
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
        return $this;
    }
    
    /**
     * Get ext
     *
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }
    
    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return Image
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }
    
    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set name
     * 
     * @param string $name            
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;        
        return $this;
    }

    /**
     * Get name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

    public function getLegal()
    {
        return $this->legal;
    }

    public function setLegal($legal)
    {
        $this->legal = $legal;
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

    public function getPublicdomain()
    {
        return $this->publicdomain;
    }

    public function setPublicdomain($publicdomain)
    {
        $this->publicdomain = $publicdomain;
        return $this;
    }

    /**
     * Set path
     * 
     * @param string $path            
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;        
        return $this;
    }

    /**
     * Get path
     * 
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.       
        return 'uploads/'.ModUtil::getVar('KaikmediaGalleryModule','upload_dir','kmgallery');
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null === $this->file) {
            return;
        }
        if (null !== $this->file) {
            // do whatever you want to generate a unique name

            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->file->guessExtension();
            
            if($this->name == ''){           
            $this->name = $this->file->getClientOriginalName();
            }
            $this->ext = $this->file->guessExtension();
            $this->mimeType = $this->file->getMimeType();
            $this->size = $this->file->getClientSize();      
            
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->file->move($this->getUploadRootDir(), $this->path);
        
        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     */
    public function isEmpty()
    {
        if (null === $this->file) {
            return true;
        }
        
        return false;
    }
    
    /**
     */
    public function toArray()
    {    
        $array =array();
        $array['id'] = $this->id;
        $array['size'] = $this->size;        
        $array['ext'] = $this->ext;
        $array['mimeType'] = $this->mimeType;
        $array['name'] = $this->name;
        $array['description'] = $this->description;
        $array['legal'] = $this->legal;
        $array['publicdomain'] = $this->publicdomain;
        $array['path'] = $this->path;
        $array['author'] = $this->author;
        $array['absolute_path'] = $this->getUploadDir().'/'.$this->path;
        $array['src'] = 'web/'.$this->getUploadDir().'/'.$this->path;       
        return $array;
    }

    /**
     *
     * @return object array
     */
    public function getMappedobjects()
    {
        return $this->mappedobjects;
    }

    /**
     *
     * @param object array $mappedobjects            
     */
    public function setMappedobjects($mappedobjects)
    {
        $this->mappedobjects = $mappedobjects;
        return $this;
    }
     
}