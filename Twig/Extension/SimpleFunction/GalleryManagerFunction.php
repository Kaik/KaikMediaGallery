<?php
/**
 * Copyright Zikula Foundation 2015 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPv3 (or at your option any later version).
 * @package Zikula
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Kaikmedia\GalleryModule\Twig\Extension\SimpleFunction;

use Symfony\Component\HttpKernel\Controller\ControllerReference;

class GalleryManagerFunction
{
    private $handler;

    /**
     * AdminBreadcrumbsFunction constructor.
     */
    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    /**
     * Inserts admin breadcrumbs.
     *
     * This has NO configuration options.
     *
     * Examples:
     *
     * <samp>{{ adminBreadcrumbs() }}</samp>
     *
     * @return string
     */
    public function display($obj_id = null, $mode = 'info')
    {
        $ref = new ControllerReference('KaikmediaGalleryModule:Plugin:manager',['obj_id' => $obj_id, 'mode' => $mode]);
        return $this->handler->render( $ref, 'inline', []);
    }
}