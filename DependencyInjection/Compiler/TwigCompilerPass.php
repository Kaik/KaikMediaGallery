<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright (C) 2017 KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * TwigCompilerPass
 *
 * @author Kaik
 */
class TwigCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('twig.form.resources')) {
            return;
        }
        $resources = $container->getParameter('twig.form.resources');
//        $resources[] = '@KaikmediaGalleryModule/Form/Providers/provider1.html.twig';
        $container->setParameter('twig.form.resources', $resources);
    }
}
