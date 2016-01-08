<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Entity\Relations;

use Doctrine\ORM\Mapping as ORM;
use Zikula\Core\Doctrine\EntityAccess;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Kaikmedia\GalleryModule\Entity\Base\AbstractBaseEntity;

/**
 * Description of BaseEntity
 *
 * @ORM\Entity(repositoryClass="Kaikmedia\GalleryModule\Entity\Repository\RelationsRepository")
 * @ORM\Table(name="kmgallery_relations")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"pages" = "KaikmediaPagesModuleRelationsEntity",
 *                        "users" = "ZikulaUsersModuleRelationsEntity"})
 * 
 * @author Kaik
 */
abstract class AbstractRelationsEntity extends AbstractBaseEntity {
        

    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     */
    public function __toString() {
        return parent::__toString();
    }

    /**
     */
    public function __clone() {
        return parent::__clone();
    }

}
