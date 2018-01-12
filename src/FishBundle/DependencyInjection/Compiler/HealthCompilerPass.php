<?php

namespace FishBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class HealthCompilerPass
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class HealthCompilerPass implements CompilerPassInterface
{
    /**
     * Add rules validators to the RulesValidator
     *
     * @param ContainerBuilder $container
     *
     * @throws ServiceNotFoundException
     * @throws InvalidArgumentException
     */
    public function process(ContainerBuilder $container)
    {
        $life = $container->getDefinition('fish.life');

        $factories = array_keys($container->findTaggedServiceIds('fish.life.obligation'));
        foreach ($factories as $factoryID) {
            $factory = new Reference($factoryID);
            $life->addMethodCall('addObligation', [$factory]);
        }
    }
}
