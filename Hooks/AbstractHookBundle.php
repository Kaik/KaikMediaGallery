<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright (C) 2017 KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Hooks;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * AbstractHookBundle
 *
 * @author Kaik
 */
abstract class AbstractHookBundle implements \ArrayAccess
{
    private $baseName;

    private $modules;

    private $settings = [];
    
    private $area;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
    }

    public function getOwner()
    {
        return 'KaikmediaGalleryModule';
    }

    public function getBaseName()
    {
        return $this->baseName;
    }

    public function setAreaName($area) {
        $this->area = $area;
        
        return $this;
    }
    public function getAreaName() {
        return $this->area;
    }
    
    public function setSettings($hooked)
    {
        $this->settings = $hooked;

        return $this;
    }

    public function setModules($modules)
    {
        $this->modules = $modules;

        return $this;
    }

    public function getModules()
    {
        return $this->modules;
    }

    public function offsetExists($offset)
    {
        switch ($offset) {
            case 'modules':

                return true;
            default:
                if (true === strpos($offset, 'Module')) {
                    return $this->modules->offsetExists($offset);
                } else {
                    return array_key_exists($offset, $this->settings);
                }
        }
    }

    public function offsetGet($offset)
    {
        switch ($offset) {
            case 'modules':

                return $this->getModules();
            default:
                if (true === strpos($offset, 'Module')) {
                    return $this->modules->offsetGet($offset);
                } else {
                    return $this->offsetExists($offset) ? $this->settings[$offset] : false;
                }
        }
    }

    public function offsetSet($offset, $value)
    {
        switch ($offset) {
            case 'modules':

                return $this->setModules($value);

            default:
                if (true === strpos($offset, 'Module')) {
                    return $this->modules->offsetSet($offset, $value);
                } else {
                    return $this->offsetExists($offset) ? $this->settings[$offset] = $value : false;
                }
        }
    }

    public function offsetUnset($offset)
    {
        switch ($offset) {
            case 'modules':

                return $this->getModules()->clear();

            default:
                if (true === strpos($offset, 'Module')) {
                    return $this->modules->offsetUnset($offset);
                } else {
                    return true;
                }
        }
    }
}
