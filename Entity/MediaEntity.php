<?php

/**
 * Copyright (c) KaikMedia.com 2015
 */

namespace Kaikmedia\GalleryModule\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Kaikmedia\GalleryModule\Entity\Base\AbstractBaseEntity;

/**
 * Media
 * @ORM\Table(name="kmgallery_media")
 * @ORM\Entity(repositoryClass="Kaikmedia\GalleryModule\Entity\Repository\MediaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MediaEntity extends AbstractBaseEntity {

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $ext;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mimeType;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     * @var string 
     */
    private $name;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $legal;

    /**
     * @ORM\Column(type="boolean")
     */
    private $publicdomain;

    /**
     * @ORM\Column(name="path", type="string", length=255)
     * @var string 
     */
    private $path;

    /**
     * @ORM\OneToMany(targetEntity="Kaikmedia\GalleryModule\Entity\MediaRelationsEntity", mappedBy="original")
     */
    protected $relations;

    /**
     * constructor
     */
    public function __construct() {
        parent::__construct();
        $this->publicdomian = 0;
        $this->relations = new ArrayCollection();
    }

    /**
     *
     * @return the unknown_type
     */
    public function getSize() {
        return $this->size;
    }

    /**
     *
     * @param unknown_type $size            
     */
    public function setSize($size) {
        $this->size = $size;
        return $this;
    }

    /**
     * Set ext
     *
     * @param string $ext
     * @return Image
     */
    public function setExt($ext) {
        $this->ext = $ext;
        return $this;
    }

    /**
     * Get ext
     *
     * @return string
     */
    public function getExt() {
        return $this->ext;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return Image
     */
    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType() {
        return $this->mimeType;
    }

    /**
     * Set name
     * 
     * @param string $name            
     * @return Image
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    public function getLegal() {
        return $this->legal;
    }

    public function setLegal($legal) {
        $this->legal = $legal;
        return $this;
    }

    public function getPublicdomain() {
        return $this->publicdomain;
    }

    public function setPublicdomain($publicdomain) {
        $this->publicdomain = $publicdomain;
        return $this;
    }

    /**
     * Set path
     * 
     * @param string $path            
     * @return Image
     */
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }

    /**
     * Get path
     * 
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.       
        return 'uploads/' . \ModUtil::getVar('KaikmediaGalleryModule', 'upload_dir', 'kmgallery');
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null === $this->file) {
            return;
        }
        if (null !== $this->file) {
            // do whatever you want to generate a unique name

            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->file->guessExtension();

            if ($this->name == '') {
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
    public function upload() {
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
    public function removeUpload() {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }

    /**
     */
    public function isEmpty() {
        if (null === $this->file) {
            return true;
        }

        return false;
    }

    /**
     */
    public function toArray() {
        $array = [];
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
        $array['absolute_path'] = $this->getUploadDir() . '/' . $this->path;
        $array['src'] = 'web/' . $this->getUploadDir() . '/' . $this->path;
        return $array;
    }

    /**
     *
     * @return object array
     */
    public function getRelations() {
        return $this->relations;
    }

    /**
     *
     * @param object array $relations            
     */
    public function setRelations($relations) {
        $this->relations = $relations;
        return $this;
    }

}
