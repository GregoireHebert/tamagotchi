<?php

Namespace Gheb\Fish\NeatBundle\Tests\Genomes;

use Doctrine\Common\Collections\ArrayCollection;
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

        $omb = $this->getMockBuilder('Gheb\Fish\NeatBundle\Aggregator');
        $output = $omb->disableOriginalConstructor()->getMock();

        $imb = $this->getMockBuilder('Gheb\Fish\NeatBundle\Aggregator');
        $input = $imb->disableOriginalConstructor()->getMock();
        $input->expects($this->once())->method('count')->willReturn(4);

        $mb = $this->getMockBuilder('Gheb\Fish\NeatBundle\Genomes\Mutation');
        $mutation = $mb->disableOriginalConstructor()->getMock();
        $mutation->expects($this->once())->method('mutate');

        $pool = new Pool($em, $output, $input, $mutation);

        $genome = $pool->createBasicGenome();
        $this->assertInstanceOf('Gheb\Fish\NeatBundle\Genomes\Genome', $genome);
        $this->assertEquals(5, $genome->getMaxNeuron());
    }

    public function testAddToSpecies()
    {
        $emb = $this->getMockBuilder('Doctrine\ORM\EntityManager');
        $em = $emb->disableOriginalConstructor()->getMock();

        $omb = $this->getMockBuilder('Gheb\Fish\NeatBundle\Aggregator');
        $output = $omb->disableOriginalConstructor()->getMock();

        $imb = $this->getMockBuilder('Gheb\Fish\NeatBundle\Aggregator');
        $input = $imb->disableOriginalConstructor()->getMock();

        $mb = $this->getMockBuilder('Gheb\Fish\NeatBundle\Genomes\Mutation');
        $mutation = $mb->disableOriginalConstructor()->getMock();

        $pool = new Pool($em, $output, $input, $mutation);

        $gmb = $this->getMockBuilder('Gheb\Fish\NeatBundle\Genomes\Genome');
        $genome = $gmb->disableOriginalConstructor()->getMock();

        $this->assertEquals(0, $pool->getSpecies()->count());

        // not found species
        $pool->addToSpecies($genome);

        $this->assertEquals(1, $pool->getSpecies()->count());
        $this->assertEquals(1, $pool->getSpecies()->first()->getGenomes()->count());
        $this->assertEquals($genome, $pool->getSpecies()->first()->getGenomes()->first());

        $genemb = $this->getMockBuilder('Gheb\Fish\NeatBundle\Genomes\Gene');
        $gene1 = $genemb->disableOriginalConstructor()->getMock();
        $gene2 = $genemb->disableOriginalConstructor()->getMock();
        $gene3 = $genemb->disableOriginalConstructor()->getMock();

        $genome->expects($this->any())->method('getGenes')->willReturn(new ArrayCollection(array($gene1)));

        $genome2 = $gmb->disableOriginalConstructor()->getMock();
        $genome3 = $gmb->disableOriginalConstructor()->getMock();

        $genome2->expects($this->any())->method('getGenes')->willReturn(new ArrayCollection(array($gene2)));
        $genome3->expects($this->any())->method('getGenes')->willReturn(new ArrayCollection(array($gene3)));

        // same species
        $gene1->expects($this->any())->method('getInnovation')->willReturn(1);
        $gene1->expects($this->any())->method('getWeight')->willReturn(1.7);
        $gene2->expects($this->any())->method('getInnovation')->willReturn(1);
        $gene2->expects($this->any())->method('getWeight')->willReturn(0.2);

        // disjoint specie
        $gene3->expects($this->any())->method('getInnovation')->willReturn(2);
        $gene3->expects($this->any())->method('getWeight')->willReturn(0.4);

        $pool->addToSpecies($genome2);
        $this->assertEquals(1, $pool->getSpecies()->count());
        $this->assertEquals(new ArrayCollection(array($genome, $genome2)), $pool->getSpecies()->first()->getGenomes());

        $pool->addToSpecies($genome3);
        $this->assertEquals(2, $pool->getSpecies()->count());
        $this->assertEquals(new ArrayCollection(array($genome, $genome2)), $pool->getSpecies()->first()->getGenomes());
        $this->assertEquals(new ArrayCollection(array($genome3)), $pool->getSpecies()->offsetGet(1)->getGenomes());
    }
}