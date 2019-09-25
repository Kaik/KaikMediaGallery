<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Settings;

use Zikula\Core\Doctrine\EntityAccess;

/**
 * Description of UploadSettings
 *
 * @author Kaik
 */
class MimeTypeSettings extends EntityAccess
{
    public $name;
    public $icon;
    public $handler;
    public $mimeType;
    public $enabled;
    
    public function __construct() {
        $this->enabled = 0;      
        $this->icon = 'fa fa-image-o';      
    } 

    public function getName(){
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
        return $this;
    }    
    
    public function getIcon(){
        return $this->icon;
    }
    
    public function setIcon($icon) {
        $this->icon = $icon;
        return $this;
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
    
    public function getHandler() {
        return $this->handler;
    }

    public function setHandler($handler) {
        $this->handler = $handler;
        return $this;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return Image
     */
    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;
        return $this;
    }

    public function mergeFromSettings($settings) {
        if (array_key_exists('enabled', $settings)){
            $this->setEnabled((int) $settings['enabled']);
        }
        
        return $this;
    }
    
    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType() {
        return $this->mimeType;
    }    
       
    public function getFormClass() {
      return '\\Kaikmedia\\GalleryModule\\Form\\Settings\\MimeTypeSettingsType';  
    }
}
