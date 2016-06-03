<?php

namespace Gheb\Fish\NeatBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class NeatExtension
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\NeatBundle\DependencyInjection
 */
class NeatExtension extends Extension
{
    /**
     * load services
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new YamlFileLoader($container, $locator);
        $loader->load('neat.yml');
    }
}