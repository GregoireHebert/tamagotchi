<?php

namespace TamagotchiBundle;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use TamagotchiBundle\DependencyInjection\Compiler\HealthCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TamagotchiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new HealthCompilerPass());
    }
}
