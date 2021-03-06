<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Hooks;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * HookedModuleObject
 *
 * @author Kaik
 */
class HookedModuleObject implements \ArrayAccess
{
    public $enabled = false;

    public $name;

    public $data;

    public $areas;

    public function __construct($name, $data)
    {
        $this->name = $name;
        $this->data = $data;
        $this->areas = new ArrayCollection();
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getAreas()
    {
        return $this->areas;
    }

    public function getName()
    {
        return $this->name;
    }

    public function offsetExists($offset)
    {
        switch ($offset) {
            case 'enabled':

                return true;
            default:

                return array_key_exists($offset, $this->data);
        }
    }

    public function offsetGet($offset)
    {
        switch ($offset) {
            case 'enabled':

                return $this->enabled;
            default:

                return $this->offsetExists($offset) ? $this->data[$offset] : false;
        }
    }

    public function offsetSet($offset, $value)
    {
        switch ($offset) {
            case 'enabled':
                $this->enabled = $value;

                return true;
            default:

                return $this->offsetExists($offset) ? $this->data[$offset] = $value : false;
        }
    }

    public function offsetUnset($offset)
    {
        return true;
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
