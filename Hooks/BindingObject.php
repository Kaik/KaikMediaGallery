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

use Zikula\Bundle\HookBundle\HookProviderInterface;
use Zikula\Bundle\HookBundle\HookSubscriberInterface;

/**
 * BindingObject
 *
 * @author Kaik
 */
class BindingObject implements \ArrayAccess
{
    public $enabled = false;

    public $provider = [];

    public $subscriber = [];

    public $settings = [];

    public $form;

    public function __construct()
    {
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

    public function getProvider()
    {
        return $this->provider;
    }

    public function getSubscriber()
    {
        return $this->subscriber;
    }

    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
    }

    public function setSubscriber($subscriber)
    {
        $this->subscriber = $subscriber;

        return $this;
    }

    public function getSubscriberClass()
    {
        return (new \ReflectionClass($this->subscriber))->getName();
    }
    
    public function getSubscriberArea()
    {
        return $this->subscriberArea;
    }   
      
    public function getMatchedEvents()
    {
        if (!$this->getSubscriber() instanceof HookSubscriberInterface) {
            
            return [];
        }
        
        if (!$this->getProvider() instanceof HookProviderInterface) {
            
            return [];
        }        

        return array_intersect_key($this->getProvider()->getProviderTypes(), $this->getSubscriber()->getEvents());
    }    
    
    public function setSubscriberArea($areaName)
    {
        $this->subscriberArea = $areaName;
        
        return $this;
    }
    
    public function getForm()
    {
        return $this->form;
    }

    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->settings);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->settings[$offset] : false;
    }

    public function offsetSet($offset, $value)
    {
        return $this->offsetExists($offset) ? $this->settings[$offset] = $value : false;
    }

    public function offsetUnset($offset)
    {
        return true;
    }

    public function __toString()
    {
        return (string) $this->offsetGet('id');
    }
}
