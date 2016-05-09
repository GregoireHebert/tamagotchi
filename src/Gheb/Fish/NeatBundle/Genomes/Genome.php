<?php

namespace Gheb\Fish\NeatBundle\Genomes;

use Doctrine\Common\Collections\ArrayCollection;

class Genome
{
    /**
     * @var ArrayCollection
     */
    public $connectionGenes;

    /**
     * @param Gene $gene
     */
    public function addConnectionGene(Gene $gene)
    {
        $this->connectionGenes->add($gene);
    }

    public function removeConnectionGene(Gene $gene)
    {
        $this->connectionGenes->removeElement($gene);
    }

    /**
     * @return ArrayCollection
     */
    public function getConnectionGenes()
    {
        return $this->connectionGenes;
    }

    /**
     * @param ArrayCollection $connectionGenes
     */
    public function setConnectionGenes($connectionGenes)
    {
        $this->connectionGenes = $connectionGenes;
    }
}
