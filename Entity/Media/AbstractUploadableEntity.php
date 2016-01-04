<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Entity\Media;

use Doctrine\ORM\Mapping as ORM;
use Zikula\Core\Doctrine\EntityAccess;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Kaikmedia\GalleryModule\Entity\Base\AbstractBaseEntity;
use Gedmo\Uploadable\Uploadable;

/**
 * @ORM\Entity(repositoryClass="Kaikmedia\GalleryModule\Entity\Repository\MediaRepository")
 * @Gedmo\Uploadable(pathMethod="getPath", callback="newUpload", filenameGenerator="SHA1", appendNumber=true)
 */
abstract class AbstractUploadableEntity extends AbstractMediaEntity implements Uploadable {    
    
    /**
     * @ORM\Column(name="path", type="string")
     * @Gedmo\UploadableFilePath
     */
    protected $path;    
    
    /**
     * @ORM\Column(name="name", type="string", length=255)
     * @Gedmo\UploadableFileName
     * @var string 
     */
    protected $name;    

    /**
     * @ORM\Column(type="string")
     * @Gedmo\UploadableFileMimeType
     *
     * @var string
     */
    protected $mimeType;
    
    /**
     * @ORM\Column(type="decimal")
     * @Gedmo\UploadableFileSize
     * 
     */
    protected $size;    

    /**
     * @ORM\Column(type="string", length=15)
     */
    protected $ext;
    

    public function __construct()
    {
        parent::__construct();

    }

    public function getPath()
    {
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

    public function getUrl()
    {
        return \System::getBaseUri() . '/' . $this->getPath();
    }

    public function newUpload(array $info)
    {
        // Do nothing for now.
        $this->ext = $info['fileExtension'];
        // fileName: The filename.
        // fileExtension: The extension of the file (including the dot). Example: .jpg
        // fileWithoutExt: The filename without the extension.
        // filePath: The file path. Example: /my/path/filename.jpg
        // fileMimeType: The mime-type of the file. Example: text/plain.
        // fileSize: Size of the file in bytes. Example: 140000.
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
     * @param string $mimeType
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

}
