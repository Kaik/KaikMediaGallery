<?php

/**
 * Copyright (c) KaikMedia.com 2015
 */

namespace Kaikmedia\GalleryModule\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Kaikmedia\GalleryModule\Entity\MediaRelationDataEntity as MediaRelationData;
use Kaikmedia\GalleryModule\Util\Settings as Settings;

/**
 * Media
 * @ORM\Table(name="kmgallery_mediarelations")
 * @ORM\Entity(repositoryClass="Kaikmedia\GalleryModule\Entity\Repository\MediaRelationsRepository")
 * 
 */
class MediaRelationsEntity extends Base\AbstractBaseEntity {

    /**
     * @ORM\Column(type="string", length=60, nullable=false)
     */
    private $obj_name;

    /**
     * @ORM\Column(type="integer", length=60, nullable=false)
     */
    private $obj_reference;

    /**
     * @ORM\ManyToOne(targetEntity="Kaikmedia\GalleryModule\Entity\MediaEntity", inversedBy="relations")
     * @ORM\JoinColumn(name="original", referencedColumnName="id")
     */
    protected $original;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $type = 'additional';

    /**
     * @ORM\OneToMany(targetEntity="Kaikmedia\GalleryModule\Entity\MediaRelationDataEntity", mappedBy="relation", cascade={"ALL"}, indexBy="name")
     * 
     */
    protected $details;

    public function addDetail($name, $value, $display) {
        $this->details[$name] = new MediaRelationData($name, $value, $display, $this);
    }

    /**
     * constructor
     * 
     */
    public function __construct() {
        parent::__construct();
        $this->details = new ArrayCollection();
    }

    /**
     * Get obj_name
     *
     * @return string
     */
    public function getObjName() {
        return $this->obj_name;
    }

    /**
     * Set obj_name
     *
     * @param string $obj_name            
     */
    public function setObjName($obj_name) {
        $this->obj_name = $obj_name;
        return $this;
    }

    /**
     * Get obj_reference
     *
     * @return integer
     */
    public function getObj_reference() {
        return $this->obj_reference;
    }

    /**
     * Set obj_reference
     * 
     * @param integer $obj_reference            
     */
    public function setObj_reference($obj_reference) {
        $this->obj_reference = $obj_reference;
        return $this;
    }

    /**
     * Get original
     * 
     * @return object $original
     */
    public function getOriginal() {
        return $this->original;
    }

    /**
     * Set original
     *
     * @param object $original            
     */
    public function setOriginal($original) {
        $this->original = $original;
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * Get details
     *
     * @return array collection
     */
    public function getDetails() {

        $settings = new Settings();

        $relation_settings = $settings->getFeatureSettings($this->type, $this->obj_name);
        if (isset($relation_settings['fields']) && $relation_settings['fields'] != '') {
            $fields = explode(',', $relation_settings['fields']);

            foreach ($this->details as $detail) {
                if (in_array($detail->getName(), $fields)) {
                    $fields = array_flip($fields);
                    unset($fields[$detail->getName()]);
                    $fields = array_flip($fields);
                }
                //Default name
                if ($detail->getName() == 'name' && $detail->getValue() == '') {
                    $detail->setValue($this->original->getName());
                }
            }

            foreach ($fields as $field_name) {
                if ($field_name == 'name') {
                    $this->addDetail($field_name, $this->original->getName(), 1);
                } else {
                    $this->addDetail($field_name, '', 1);
                }
            }
        }

        return $this->details;
    }

    /**
     * Set details
     *
     * @return integer
     */
    public function setDetails($details) {
        $this->details = $details;
    }

    /**
     */
    public function toArray() {
        $array = array();
        $array['id'] = $this->id;
        $array['obj_name'] = $this->obj_name;
        $array['obj_reference'] = $this->obj_reference;
        $array['original_arr'] = $this->original->toArray();
        $array['author'] = $this->author;
        return $array;
    }

}
