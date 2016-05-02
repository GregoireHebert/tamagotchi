<?php

namespace Gheb\Fish\FishBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class HealthCompilerPass
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\DependencyInjection\Compiler
 */
class HealthCompilerPass implements CompilerPassInterface
{
    /**
     * Add rules validators to the RulesValidator
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $validator = $container->getDefinition('fish.life.obligation');

        $factories = array_keys($container->findTaggedServiceIds('fish.life.obligation'));
        foreach ($factories as $factoryID) {
            $factory = new Reference($factoryID);
            $validator->addMethodCall('addObligation', array($factory));
        }
    }
}