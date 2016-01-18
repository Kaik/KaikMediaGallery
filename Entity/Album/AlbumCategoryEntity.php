<?php
/**
 * 
 */

namespace Kaikmedia\GalleryModule\Entity\Album;

use Doctrine\ORM\Mapping as ORM;
use Zikula\Core\Doctrine\Entity\AbstractEntityCategory;

/**
 * Pages entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="kmgallery_albums_category",
 *            uniqueConstraints={@ORM\UniqueConstraint(name="cat_unq",columns={"registryId", "categoryId", "entityId"})})
 */
class AlbumCategoryEntity extends AbstractEntityCategory
{
    /**
     * @ORM\ManyToOne(targetEntity="Kaikmedia\GalleryModule\Entity\Album\AlbumEntity", inversedBy="category")
     * @ORM\JoinColumn(name="entityId", referencedColumnName="id")
     * @var Kaikmedia\GalleryModule\Entity\Album\AlbumEntity
     */
    private $entity;

    /**
     * Set entity
     *
     * @return \Kaikmedia\PagesModule\Entity\PageEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set entity
     *
     * @param \Kaikmedia\PagesModule\Entity\PageEntity $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

}