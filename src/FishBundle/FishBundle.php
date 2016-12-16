<?php
namespace FishBundle;

use FishBundle\DependencyInjection\Compiler\HealthCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FishBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new HealthCompilerPass());
    }
}
