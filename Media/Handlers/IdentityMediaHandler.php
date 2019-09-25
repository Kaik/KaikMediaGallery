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

class IdentityMediaHandler implements MediaHandlerInterface
{
    private $objectId;

    /**
     * {@inheritdoc}
     */
    public function getTitle() {
        return 'IdentityMediaHandler';
    }

    public function getIcon() {
        return 'fa fa-file-o';
    }

    public function getMimeTypeData($mimeType) {
        return [];
    }

    public function getMimeTypeIcon($mimeType) {
        return 'fa fa-file-o';
    }

    public function getPreviewForDisplay($media, $options) {
        return 'IdentityMediaHandler';
    }

    public function getPreviewForEdit($media, $options) {
        return 'IdentityMediaHandler';
    }

    public function getPreviewHtml($settings) {
        return 'IdentityMediaHandler';
    }

    public function getSupportedMimeTypes() {
        return [];
    }

    public function isFile() {
        return false;
    }

    public function isText() {
        return false;
    }
}