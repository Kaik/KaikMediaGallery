<?php

/**
 * Copyright (c) KaikMedia.com 2015
 */

namespace Kaikmedia\GalleryModule\Entity\Album;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Kaikmedia\GalleryModule\Entity\Base\AbstractDocumentEntity;

/**
 * Media
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="kmgallery_albums")
 * @ORM\Entity(repositoryClass="Kaikmedia\GalleryModule\Entity\Repository\AlbumsRepository")
 */
class AlbumEntity extends AbstractDocumentEntity {

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
     * categories
     *
     * @ORM\OneToMany(targetEntity="Kaikmedia\GalleryModule\Entity\Album\AlbumCategoryEntity",
     *                mappedBy="entity", cascade={"remove", "persist"},
     *                orphanRemoval=true, fetch="EAGER")
     */
    private $category;

    /**
     * constructor
     */
    public function __construct() {
        parent::__construct();
        
        $this->category = new ArrayCollection();
    }

    /**
     * Set parent
     *
     * @return string
     */
    public function setParent(AlbumEntity $parent = null) {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return string
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Get js tree array
     *
     * @return integer
     */
    public function getAsJsTreeArray() {
        $parent = $this->parent == null ? '#' : $this->parent->getId();
        $arrayTree = array('id' => $this->id,
            'text' => $this->getTitle(),
            'parent' => $parent
        );

        return $arrayTree;
    }

    public function getMedia() {
        //$em = \ServiceUtil::getService('doctrine.entitymanager');
       // $thisMedia = $em->getRepository('Kaikmedia\GalleryModule\Entity\MediaRelationsEntity')
       //         ->getAll(array('obj_name' => 'KaikmediaGalleryModule',
       //     'obj_id' => $this->id
       // ));
         $thisMedia = false;
        return $thisMedia;
    }

    public function __toString() {
        return $this->title;
    }
    
    /**
     * Get page category assignments
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Set page category assignments
     *
     * @param ArrayCollection $assignments
     */
    public function setCategory(ArrayCollection $assignments) {
        foreach ($this->category as $categoryAssignment) {
            if (false === $key = $this->collectionContains($assignments, $categoryAssignment)) {
                $this->category->removeElement($categoryAssignment);
            } else {
                $assignments->remove($key);
            }
        }
        foreach ($assignments as $assignment) {
            $this->category->add($assignment);
        }
    }    
}
