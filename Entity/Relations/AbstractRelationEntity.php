<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright (C) 2017 KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Entity\Relations;

use Kaikmedia\GalleryModule\Entity\Base\AbstractBaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Core\Doctrine\EntityAccess;

/**
 * Description of BaseEntity
 * @ORM\MappedSuperclass()
 *
 * @author Kaik
 */
abstract class AbstractRelationEntity extends AbstractBaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Kaikmedia\GalleryModule\Entity\Media\AbstractMediaEntity", inversedBy="relations")
     * @ORM\JoinColumn(name="media", referencedColumnName="id")
     */
    private $media;

    /**
     * @ORM\Column(type="array")
     *
     * @var array
     */
    private $mediaExtra = [];

    /**
     * @ORM\Column(type="string", length=60, nullable=false)
     */
    private $feature = '';

    /**
     * @ORM\Column(type="array")
     *
     * @var array
     */
    private $featureExtra = [];

    /**
     * @ORM\Column(type="array")
     *
     * @var array
     */
    private $relationExtra = [];

    /**
     * Get Media
     *
     * @return object media
     */
    public function getMedia()
    {
        return $this->media;
    }
    
    /**
     * Get Media id
     *
     * @return object media
     */
    public function getMediaId()
    {
        return $this->media ? $this->media->getId() : null;
    }

    /**
     * Set Media

     * @param object mediaE
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get
     *
     * @return
     */
    public function getMediaExtra()
    {
        return $this->mediaExtra;
    }

    /**
     * Set

     * @param
     */
    public function setMediaExtra($mediaExtra)
    {
        $this->mediaExtra = $mediaExtra;

        return $this;
    }

    /**
     * Get
     *
     * @return
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * Set

     * @param
     */
    public function setFeature($feature)
    {
        $this->feature = $feature;

        return $this;
    }

    /**
     * Get
     *
     * @return
     */
    public function getFeatureExtra()
    {
        return $this->featureExtra;
    }

    /**
     * Set

     * @param
     */
    public function setFeatureExtra($featureExtra)
    {
        $this->featureExtra = $featureExtra;
        return $this;
    }

    /**
     * Get
     *
     * @return
     */
    public function getRelationExtra()
    {
        return $this->relationExtra;
    }

    /**
     * Get
     *
     * @return
     */
    public function getLastMediaHandler()
    {
        return $this->getMedia() !== null ? $this->getMedia()->getLastHandler() : null ;
    }

    /**
     * Set
     *
     * @param
     */
    public function setRelationExtra($relationExtra)
    {
        $this->relationExtra = $relationExtra;

        return $this;
    }

    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     */
    public function __toString()
    {
        return parent::__toString();
    }

    /**
     */
    public function __clone()
    {
        return parent::__clone();
    }
}