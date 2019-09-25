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

use Zikula\Common\Translator\TranslatorInterface;

/**
 * Description of YoutubeHandler
 *
 * @author Kaik
 */
class YoutubeHandler extends AbstractMediaHandler 
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /*
     * sypported mimetypes
     */
    private $mimeTypes = [
        'video:youtube' => ['handler' => 'youtube', 'icon' => 'fa fa-youtube', 'name' => 'video_youtube'],
    ];
    
    /**
     * Construct the handler
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(
        TranslatorInterface $translator
    ) {
        parent::__construct();
        
        $this->translator = $translator;    
    }

    public function getTitle($multiple = false) {
        return $multiple ? $this->translator->__('Add YouTube') : $this->translator->__('Add YouTube');
    }

    public function getIcon() {
        return 'fa fa-youtube';
    }  

    public function isFile(){
        return false;
    } 
    
    public function isText(){
        return true;
    }    

    public function getPreviewHtml($settings) {
        return 'fa fa-file-image-o';
    }     
    
    public function getPreviewForDisplay($media, $options) {
        
    }
    
    public function getPreviewForEdit($media, $options) {
        
    }

    public function getSupportedMimeTypes() {
        return $this->mimeTypes;
    }

    public function getMimeTypeData($mimeType) {
        return array_key_exists($mimeType, $this->mimeTypes) ? $this->mimeTypes[$mimeType] : [];
    }    

    public function getMimeTypeIcon($mimeType) {
        return array_key_exists($mimeType, $this->mimeTypes) ? $this->mimeTypes[$mimeType]['icon'] : '';
    }
}