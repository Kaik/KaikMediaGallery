<?php
/**
 * Copyright (c) KaikMedia.com 2015
 */
namespace Kaikmedia\GalleryModule\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Implementation class for service definition loader using the DependencyInjection extension.
 */
class KaikmediaGalleryExtension extends Extension
{

    /**
     * Loads service definition file containing persistent event handlers.
     * Responds to the app.config configuration parameter.
     * 
     * @param array $configs            
     * @param ContainerBuilder $container            
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        
        $loader->load('services.yml');
    }
}
