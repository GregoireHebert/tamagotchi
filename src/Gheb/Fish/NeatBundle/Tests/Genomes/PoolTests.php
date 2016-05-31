<?php

Namespace Gheb\Fish\NeatBundle\Tests\Genomes;

use Gheb\Fish\NeatBundle\Genomes\Mutation;
use Gheb\Fish\NeatBundle\Genomes\Pool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PoolTests extends KernelTestCase
{
    private $container;

    public function setUp()
    {
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
    }

    public function testCreateBasicGenome()
    {
        $emb = $this->getMockBuilder('Doctrine\ORM\EntityManager');
        $em = $emb->disableOriginalConstructor()->getMock();

        $output = $this->container->get('fish.io.aggregator.outputs');
        $input = $this->container->get('fish.io.aggregator.inputs');

        $mb = $this->getMockBuilder('Gheb\Fish\NeatBundle\Genomes\Mutation');
        $mutation = $mb->disableOriginalConstructor()->getMock();
        $mutation->expects($this->once())->method('mutate');

        $pool = new Pool($em, $output, $input, $mutation);

        $genome = $pool->createBasicGenome();
        $this->assertInstanceOf('Gheb\Fish\NeatBundle\Genomes\Genome', $genome);
        $this->assertEquals($input->count()+1, $genome->getMaxNeuron());
    }
}