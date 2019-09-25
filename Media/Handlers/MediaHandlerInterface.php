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

/**
 * Interface MediaHandlerInterface
 */
interface MediaHandlerInterface
{
    public function getTitle();
    public function getIcon();
    public function getPreviewHtml($settings);    
    public function getPreviewForDisplay($media, $options);    
    public function getPreviewForEdit($media, $options);
    public function getSupportedMimeTypes();
    public function getMimeTypeData($mimeType);    
    public function getMimeTypeIcon($mimeType);
    public function isFile();
    public function isText();
}
