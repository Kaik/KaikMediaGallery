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
use Zikula\Core\UrlInterface;

/**
 * Description
 * @ORM\Table(name="kmgallery_hooks_relations")
 * @ORM\Entity(repositoryClass="Kaikmedia\GalleryModule\Entity\Repository\RelationsRepository")
 * @author Kaik
 */
class HooksRelationsEntity extends AbstractRelationEntity
{
    /**
     * module field (hooked module name)
     *
     * @ORM\Column(length=50, nullable=true)
     */
    private $hookedModule;

    /**
     * areaId field (hooked area id)
     *
     * @ORM\Column(length=100, nullable=true)
     */
    private $hookedAreaId;

    /**
     * objectId field (object id)
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hookedObjectId;

    /**
     * url object
     * @var UrlInterface
     *
     * @ORM\Column(type="object", nullable=true)
     */
    private $hookedUrlObject = null;


    public function getHookedModule()
    {
        return $this->hookedModule;
    }

    public function setHookedModule($hookedModule)
    {
        $this->hookedModule = $hookedModule;

        return $this;
    }

    public function getHookedAreaId()
    {
        return $this->hookedAreaId;
    }

    public function setHookedAreaId($hookedAreaId)
    {
        $this->hookedAreaId = $hookedAreaId;

        return $this;
    }

    public function getHookedObjectId()
    {
        return $this->hookedObjectId;
    }

    public function setHookedObjectId($hookedObjectId)
    {
        $this->hookedObjectId = $hookedObjectId;

        return $this;
    }

    public function getHookedUrlObject()
    {
        return $this->hookedUrlObject;
    }

    public function setHookedUrlObject(UrlInterface $hookedUrlObject = null)
    {
        $this->hookedUrlObject = $hookedUrlObject;

        return $this;
    }
}