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

/**
 * AbstractProBundle
 *
 * @author Kaik
 */
abstract class AbstractProBundle extends AbstractHookBundle
{
    public function __construct()
    {
        $this->baseName = str_replace('ProBundle', 'Provider', str_replace('Kaikmedia\GalleryModule\Hooks\\', '', get_class($this)));

        parent::__construct();
    }

    public function getProviderSettingsForArea()
    {
        return [
//            'enabled' => 0, 
        ];
    }
    
    public function getSettingsForm()
    {
        return 'Kaikmedia\\GalleryModule\\Form\\Type\\Hook\\ProviderSettingsType';
    }

    public function getBindingForm()
    {
        return false;
    }
}
