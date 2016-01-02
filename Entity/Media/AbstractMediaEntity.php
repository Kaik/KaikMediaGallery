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

/**
 * Media
 * @ORM\Table(name="kmgallery_media")
 * @ORM\Entity(repositoryClass="Kaikmedia\GalleryModule\Entity\Repository\MediaRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 * @ORM\InheritanceType(value="SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr") 
 */
abstract class AbstractMediaEntity extends AbstractBaseEntity {

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
     */
    public function isEmpty() {
        if (null === $this->file) {
            return true;
        }

        return false;
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
