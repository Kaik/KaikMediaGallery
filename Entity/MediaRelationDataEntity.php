<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Media
 * @ORM\Table(name="kmgallery_mediarelationdata")
 * @ORM\Entity 
 * (repositoryClass="Kaikmedia\GalleryModule\Entity\Repository\MediaRelationDataRepository")
 * 
 */
class MediaRelationDataEntity
{

    /**
     * @var integer 
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=160, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $value;  
        
    /**
     * @ORM\ManyToOne(targetEntity="Kaikmedia\GalleryModule\Entity\MediaRelationsEntity", inversedBy="details")
     * @ORM\JoinColumn(name="relation", referencedColumnName="id")
     */
    protected $relation;    

    /**
     * @ORM\Column(type="boolean")
     */
    private $display = false;    
    
    
    /**
     * constructor
     */
    public function __construct($name, $value, $display ,$relation)
    {
    	$this->name = $name;
    	$this->value = $value;
    	$this->relation = $relation;
    	$this->display = $display;
   
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name            
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value
     * 
     * @param integer $value            
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get relation
     * 
     * @return object $relation
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * Set relation
     *
     * @param object $relation            
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;
        return $this;
    }
    
    /**
     * Get display
     *
     * @return string
     */
    public function getDisplay()
    {
    	return $this->display;
    }
    
    /**
     * Set display
     *
     * @param string $display
     */
    public function setDisplay($display)
    {
    	$this->display = $display;
    	return $this;
    }     
     
    /**
     */
    public function toArray()
    {
        $array = array();
        $array['id'] = $this->id;
        $array['name'] = $this->name;
        $array['value'] = $this->value;
        $array['relation'] = $this->relation->getId();
        $array['display'] = $this->display;
        return $array;
    }   
}