<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Entity\Media;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractUploadableEntity extends AbstractMediaEntity {

    /**
     */
    protected $path;

    /**
     * @var string 
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $mimeType;

    /**
     * 
     */
    protected $size;

    /**
     */
    protected $ext;

    /**
     */
    private $file;

    /**
     */
    private $temp;

    public function __construct() {
        parent::__construct();

        $this->setTitle('Title');
    }

    public function getPath() {
        return \FileUtil::getDataDirectory() . '/kmgallery/media';
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
     * Set mimeType
     *
     * @param string $name
     * @return Image
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        $this->setDescription('lala2');
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->getFile()->guessExtension();
            $this->addMediaExtra();             
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {      
        if (null === $this->getFile()) {
            return;
        }
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }
    
    public function getUploadRootDir() {
        return \FileUtil::getDataDirectory() . '/kmgallery/media';
    }

    private function addMediaExtra() {
        $mediaEx = [
            'path' => $this->path,
            'mimeType' => $this->mimeType,
            'size' => $this->size,
            'ext' => $this->ext,
            'name' => $this->name
        ];
        $this->setMediaExtra($mediaEx);
        return $this;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        //$file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }

    public function isUploadable() {
        return true;
    }

}
