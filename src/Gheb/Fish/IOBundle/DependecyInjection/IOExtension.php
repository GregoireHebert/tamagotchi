<?php

namespace Gheb\Fish\IOBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class IOExtension
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\DependencyInjection
 */
class IOExtension extends Extension
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
        $loader->load('IO.yml');
    }
}