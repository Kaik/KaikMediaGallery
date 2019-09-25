<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MediaHandlersCollectorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('kaikmedia_gallery_module.internal.media_hadlers_collector')) {
            return;
        }

        $definition = $container->getDefinition('kaikmedia_gallery_module.internal.media_hadlers_collector');

        $bundleName = '';
        foreach ($container->findTaggedServiceIds('kaikmedia.gallery.media_hadler') as $id => $tagParameters) {
            foreach ($tagParameters as $tagParameter) {
                if (!isset($tagParameter['handlerName'])) {
                    throw new \InvalidArgumentException(sprintf('Service "%s" must define the "handlerName" attribute on "kaikmedia.gallery.media_hadler" tags.', $id));
                }
                $handlerName = $tagParameter['handlerName'];
            }

            $definition->addMethodCall('add', [$handlerName, new Reference($id)]);
        }
    }
}
