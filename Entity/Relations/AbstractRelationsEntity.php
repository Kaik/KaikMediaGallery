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
abstract class AbstractRelationsEntity extends AbstractBaseEntity
{
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