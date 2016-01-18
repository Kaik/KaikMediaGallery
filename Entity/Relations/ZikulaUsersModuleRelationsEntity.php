<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Entity\Relations;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description 
 * @ORM\Table(name="kmgallery_users_relations")
 * @ORM\Entity(repositoryClass="Kaikmedia\GalleryModule\Entity\Repository\RelationsRepository")
 * @author Kaik
 */
class ZikulaUsersModuleRelationsEntity extends AbstractRelationEntity {
       
    /**
     * @ORM\ManyToOne(targetEntity="Zikula\UsersModule\Entity\UserEntity")
     * @ORM\JoinColumn(name="objectId", referencedColumnName="uid")
     */
    private $objectId;
    
  /**
     * Get Object
     * 
     * @return object media
     */
    public function getObjectId() {
        return $this->objectId;
    }

    /**
     * Set Media
     
     * @param object mediaE         
     */
    public function setObjectId($objectId) {
        $this->objectId = $objectId;
        return $this;
    }      
}

