<?php

/**
 * Copyright Zikula Foundation 2015 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license MIT
 * @package ZikulaAdminModule
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Kaikmedia\GalleryModule\Twig\Extension;

use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Kaikmedia\GalleryModule\Twig\Extension\SimpleFunction\GalleryManagerFunction;

class KMGalleryExtension extends \Twig_Extension
{
    private $handler;

    /**
     * constructor.
     */
    public function __construct(FragmentHandler $handler)
    {
        $this->handler = $handler;
    }

    public function getName()
    {
        return 'kaikmedia_gallery_module';
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('galleryManager', [new GalleryManagerFunction($this->handler), 'display'], ['is_safe' => ['html']])
        ];
    }
}
