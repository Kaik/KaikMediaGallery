<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zikula\Core\AbstractModule;
use Kaikmedia\GalleryModule\DependencyInjection\Compiler\TwigCompilerPass;
use Kaikmedia\GalleryModule\DependencyInjection\Compiler\MediaHandlersCollectorPass;

class KaikmediaGalleryModule extends AbstractModule
{
    const NAME = 'KaikmediaGalleryModule';

    /**
     * {@inheritdoc}
     *
     * Adds compiler passes to the container.
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TwigCompilerPass());
        $container->addCompilerPass(new MediaHandlersCollectorPass());
    }
}