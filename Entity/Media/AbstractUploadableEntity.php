<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright (C) 2017 KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Entity\Media;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractUploadableEntity extends AbstractMediaEntity
{
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

    /**
     */
    private $prefix;

    /**
     */
    private $dir;
   
    /**
     */
    private $uploadDir;

    public function __construct()
    {
        parent::__construct();
    }


    public function getUploadRootDir()
    {
        return $this->uploadDir;
    }    

    public function setUploadRootDir($uploadDir)
    {
        $this->uploadDir = $uploadDir;
        
        return $this;
    }    
    
    public function getPath()
    {
        return $this->path;
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
     * Set prefix
     *
     * @param string $name
     * @return Image
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }
    
    /**
     * Set Dir
     *
     * @param string $dir
     * @return string
     */
    public function setDir($dir)
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * Get dir
     *
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }
//        // check if we have an old image path
//        if (isset($this->path)) {
//            // store the old name to delete after the update
//            $this->temp = $this->path;
//            $this->path = null;
//        } else {
//            $this->path = 'initial';
//        }
    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->getFromMediaExtra();        
//            $this->generateTitle();
            $this->generateName();
            $this->generatePath();
            $this->generateMediaExtra();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }
        $this->generateExifExtra();
//        // if there is an error when moving the file, an exception will
//        // be automatically thrown by move(). This will properly prevent
//        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getPath(), $this->getName());
//
//        // check if we have an old image
//        if (isset($this->temp)) {
//            // delete the old image
//            unlink($this->getUploadRootDir() . '/' . $this->temp);
//            // clear the temp image path
//            $this->temp = null;
//        }
        
        
        
        $this->file = null;
    }

    private function generateTitle()
    {
        $this->setTitle($this->getFile()->getClientOriginalName());
        
        return $this;
    }

    private function generateName()
    {
        $this->setName($this->getPrefix().uniqid().".".$this->getFile()->getClientOriginalExtension());
        
        return $this;
    }
    
    private function generatePath()
    {
        $path = $this->getUploadRootDir();

        if ($this->getDir()){
            if (is_writable($this->getUploadRootDir().'/'.$this->getDir())) {
                $path = $this->getUploadRootDir().'/'.$this->getDir();
            } elseif (mkdir($this->getUploadRootDir().'/'.$this->getDir(), 0775, true)) {
                $path = $this->getUploadRootDir().'/'.$this->getDir();
            }           
        }

        $this->setPath($path);
            
        return $this;
    }
    
    private function getFromMediaExtra()
    {
        $mediaExtra = $this->getMediaExtra();
        $this->setDir($mediaExtra['dir']);
        $this->setPrefix($mediaExtra['prefix']);
        
        return $this;
    }
    
    private function generateMediaExtra()
    {
        $mediaExtra = $this->getMediaExtra();
        
        $mediaExtra['savedAs'] = $this->getName();
        $mediaExtra['original'] = $this->getFile()->getClientOriginalName();
        $mediaExtra['exif'] = getimagesize($this->getFile()->getRealPath());
        $mediaExtra['ext'] = $this->getFile()->getClientOriginalExtension();
        $this->setMediaExtra($mediaExtra);
 
        return $this;
    }
    private function generateExifExtra()
    {
//        $mediaExtra = $this->getMediaExtra();
        

//        $this->setMediaExtra($mediaExtra);
 
        return $this;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        //$file = $this->getAbsolutePath();
//        if ($file) {
//            unlink($file);
//        }
    }

    public function isUploadable()
    {
        return true;
    }
}