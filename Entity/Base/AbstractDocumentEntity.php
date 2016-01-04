<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Zikula\Core\Doctrine\EntityAccess;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of BaseEntity
 *
 * @ORM\MappedSuperclass()
 * 
 * @author Kaik
 */
abstract class AbstractDocumentEntity extends AbstractBaseEntity {
     
    /**
     * title
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $title = '';

    /**
     * urltitle
     *
     * @ORM\Column(type="text")
     * @Gedmo\Slug(fields={"title"})
     */
    private $urltitle = '';
  
    /**
     * @ORM\Column(type="text", length=255)
     */
    private $description = '';
   
    //status boleans 
    /**
     * @ORM\Column(type="boolean")
     */
    private $online;

    /**
     * @ORM\Column(type="boolean")
     */
    private $depot;

    /**
     * @ORM\Column(type="boolean")
     */
    private $inmenu;

    /**
     * @ORM\Column(type="boolean")
     */
    private $inlist;
    
    //other
    /**
     * @ORM\Column(type="string", length=5)
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $layout;

    /**
     * @ORM\Column(type="integer")
     */
    private $views;
    
    /**
     * The author uid
     * @ORM\ManyToOne(targetEntity="Zikula\Module\UsersModule\Entity\UserEntity")
     * @ORM\JoinColumn(name="author", referencedColumnName="uid")
     */
    private $author;    

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expiredAt;

    /**
     * constructor
     */
    public function __construct() {
        $this->online = 0;
        $this->depot = 0;
        $this->inmenu = 1;
        $this->inlist = 1;
        $em = \ServiceUtil::getService('doctrine.entitymanager');
        $this->author = $em->getRepository('Zikula\Module\UsersModule\Entity\UserEntity')->findOneBy(array(
            'uid' => \UserUtil::getVar('uid')
        ));
        $this->language = 'all';
        $this->layout = 'default';
        $this->views = 0;

        // $this->attributes = new ArrayCollection();
    }

    /**
     * Set title
     * 
     * @param string $title            
     * @return Pages
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     * 
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set urltitle
     * 
     * @param string $urltitle            
     * @return Pages
     */
    public function setUrltitle($urltitle) {
        $this->urltitle = $urltitle;

        return $this;
    }

    /**
     * Get urltitle
     * 
     * @return string
     */
    public function getUrltitle() {
        return $this->urltitle;
    }

    /**
     * Get description
     * 
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set description
     * 
     * @return $this
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Set online
     * 
     * @param boolean $online            
     * @return Pages
     */
    public function setOnline($online) {
        $this->online = $online;

        return $this;
    }

    /**
     * Get online
     * 
     * @return boolean
     */
    public function getOnline() {
        return $this->online;
    }

    /**
     * Set depot
     * 
     * @param boolean $depot            
     * @return Pages
     */
    public function setDepot($depot) {
        $this->depot = $depot;

        return $this;
    }

    /**
     * Get depot
     * 
     * @return boolean
     */
    public function getDepot() {
        return $this->depot;
    }

    /**
     * Set inmenu
     * 
     * @param boolean $inmenu            
     * @return Pages
     */
    public function setInmenu($inmenu) {
        $this->inmenu = $inmenu;

        return $this;
    }

    /**
     * Get inmenu
     * 
     * @return boolean
     */
    public function getInmenu() {
        return $this->inmenu;
    }

    /**
     * Set inlist
     * 
     * @param boolean $inlist            
     * @return Pages
     */
    public function setInlist($inlist) {
        $this->inlist = $inlist;

        return $this;
    }

    /**
     * Get inlist
     * 
     * @return boolean
     */
    public function getInlist() {
        return $this->inlist;
    }

    /**
     * Set language
     * 
     * @param string $language            
     * @return Pages
     */
    public function setLanguage($language) {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     * 
     * @return string
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Set layout
     * 
     * @param string $layout            
     * @return Pages
     */
    public function setLayout($layout) {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get layout
     * 
     * @return string
     */
    public function getLayout() {
        return $this->layout;
    }

    /**
     * Set views
     * 
     * @param integer $views            
     * @return Pages
     */
    public function setViews($views) {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     * 
     * @return integer
     */
    public function getViews() {
        return $this->views;
    }

    /**
     * Set author
     * 
     * @param integer $author            
     * @return Pages
     */
    public function setAuthor(\Zikula\Module\UsersModule\Entity\UserEntity $author = null) {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     * 
     * @return integer
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * Set published
     * 
     * @param \DateTime $publishedAt            
     * @return Pages
     */
    public function setPublishedAt(\DateTime $publishedAt = null) {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get published
     * 
     * @return \DateTime
     */
    public function getPublishedAt() {
        return $this->publishedAt;
    }

    /**
     * Set expired
     * 
     * @param \DateTime $expiredAt            
     * @return Pages
     */
    public function setExpiredAt(\DateTime $expiredAt = null) {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * Get expired
     * 
     * @return \DateTime
     */
    public function getExpiredAt() {
        return $this->expiredAt;
    }

    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     */
    public function __toString() {
        return $this->getId();
    }

    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.php.net/manual/en/language.oop5.cloning.php
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     * (4) http://www.pantovic.com/article/26/doctrine2-entity-cloning
     */
    public function __clone() {
        // If the entity has an identity, proceed as normal.
        if ($this->id) {
            // unset identifiers
            $this->setId(0);

            $this->setCreatedAt(null);
            $this->setCreatedBy(null);
            $this->setUpdatedAt(null);
            $this->setUpdatedBy(null);
        }
        // otherwise do nothing, do NOT throw an exception!
    }

}
