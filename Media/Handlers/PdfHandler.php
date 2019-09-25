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
 * Description of PdfHandler
 *
 * @author Kaik
 */
class PdfHandler extends AbstractMediaHandler {

    //put your code here

    public function __construct() {
        parent::__construct();
        
    }

    public function getSupportedMimeTypes() {
    return [
            'application/pdf' => ['handler' => 'pdf', 'icon' => 'fa fa-file-pdf-o', 'name' => 'application:pdf'],
            'application/x-pdf' => ['handler' => 'pdf', 'icon' => 'fa fa-file-pdf-o', 'name' => 'application:x-pdf'],
        ];
    }
}