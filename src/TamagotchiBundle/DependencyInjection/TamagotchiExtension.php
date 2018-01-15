<?php

namespace TamagotchiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class TamagotchiExtension
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class TamagotchiExtension extends Extension
{
    /**
     * load services
     *
     * @param array            $config
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader  = new YamlFileLoader($container, $locator);
        $loader->load('services.yml');
    }
}
