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
use Doctrine\Common\Collections\ArrayCollection;
use Kaikmedia\GalleryModule\Entity\Base\AbstractDocumentEntity;

/**
 * Media
 * @ORM\Table(name="kmgallery_media")
 * @ORM\Entity(repositoryClass="Kaikmedia\GalleryModule\Entity\Repository\MediaRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 * @ORM\InheritanceType(value="SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr") 
 * @ORM\DiscriminatorMap({"image" = "ImageEntity",
 *                         "pdf" = "PdfEntity",
 *                         "youtube" = "YoutubeEntity",
 *                         "url" = "UrlEntity",
 *                         "file"  = "AbstractUploadableEntity"})
 */
abstract class AbstractMediaEntity extends AbstractDocumentEntity {

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $legal;

    /**
     * @ORM\Column(type="boolean")
     */
    private $publicdomain;   
    
    /**
     * @ORM\Column(type="array")
     */
    private $mediaExtra;      
    
    /**
     * @ORM\OneToMany(targetEntity="Kaikmedia\GalleryModule\Entity\Relations\AbstractRelationEntity", mappedBy="media")
     */
    protected $relations;

    /**
     * constructor
     */
    public function __construct() {
        parent::__construct();
        $this->publicdomian = 0;
        $this->legal = 'unknow';
        $this->relations = new ArrayCollection();
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
    public function getMediaExtra() {
        return $this->mediaExtra;
    }

    public function setMediaExtra($mediaExtra) {
        $this->mediaExtra = $mediaExtra;
        return $this;
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
    
    public function isUploadable() {
        return false;
    }
}
