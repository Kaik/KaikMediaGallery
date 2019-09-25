<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Media\Handlers;

use Kaikmedia\GalleryModule\Media\Handlers\MediaHandlerInterface;
use Zikula\Core\Doctrine\EntityAccess;

/**
 *
 * @author Kaik
 */
abstract class AbstractMediaHandler extends EntityAccess implements MediaHandlerInterface 
{
    public $name;
    
    public $type;
    
    public $enabled;
    
    public function __construct() {
        $this->setName($this->getAlias());
        $this->setType(substr($this->name,0,-7));
    }   
    
    public function getName(){
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function setType($type) {
        $this->type = $type;
        return $this;
    }
    
    public function getEnabled() {
        return $this->enabled;
    }
    
    public function setEnabled($enabled) {
        $this->enabled = $enabled;
        return $this;
    }
   
    public function getFormClass() {
        return '\Kaikmedia\\GalleryModule\\Form\\Media\\' . ucfirst($this->type). 'MediaType';  //default
    }  
 
    public function getEntityClass() {
        return '\Kaikmedia\\GalleryModule\\Entity\\Media\\' . ucfirst($this->type) . 'Entity';  //mediaitem class
    }    

    public function getClass() {
        return '\Kaikmedia\\GalleryModule\\Entity\\Media\\' . ucfirst($this->type) . 'Entity';  //mediaitem class
    }
    
    public function getAlias()
    {
        $class = get_class($this);
        $class = explode('\\', $class);
        $class = $class[count($class) - 1];
        return lcfirst($class);
    }
    
    public function __toString()
    {
        return (string) $this->getName();
    }
}