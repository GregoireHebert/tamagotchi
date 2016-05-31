<?php

namespace Gheb\Fish\NeatBundle\Genomes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Gheb\Fish\IOBundle\Inputs\InputsAggregator;
use Gheb\Fish\IOBundle\Outputs\OutputsAggregator;
use Gheb\Fish\NeatBundle\Aggregator;

/**
 * Class Pool regroups every species
 * @package Gheb\Fish\NeatBundle\Genomes
 */
class Pool
{
    const CROSSOVER_CHANCE = 0.75;
    const STALE_SPECIES = 15;
    const POPULATION = 300;
    const DELTA_DISJOINT = 2.0;
    const DELTA_WEIGHT = 0.4;
    const DELTA_THRESHOLD = 1.0;

    /**
     * @var int
     */
    public $currentGenome = 1;

    /**
     * @var int
     */
    public $currentSpecies = 1;

    /**
     * @var EntityManager
     */
    public $em;

    /**
     * @var int
     */
    public $generation = 0;

    /**
     * @var int
     */
    public $innovation = 0;

    /**
     * @var InputsAggregator
     */
    private $inputAggregator;

    /**
     * @var int
     */
    public $maxFitness = 0;

    /**
     * @var Mutation
     */
    public $mutation;

    /**
     * @var ArrayCollection
     */
    public $species;

    /**
     * Pool constructor.
     *
     * @param EntityManager $em
     * @param Aggregator    $outputsAggregator
     * @param Aggregator    $inputsAggregator
     * @param Mutation      $mutation
     */
    public function __construct(EntityManager $em, Aggregator $outputsAggregator, Aggregator $inputsAggregator, Mutation $mutation)
    {
        $this->em = $em;
        $this->innovation = $outputsAggregator->count();
        $this->inputAggregator = $inputsAggregator;
        $this->mutation = $mutation;

        $this->species = new ArrayCollection();
    }

    public function addSpecie(Specie $specie)
    {
        $this->species->add($specie);
        $specie->setPool($this);
    }

    public function addToSpecies(Genome $child)
    {
        $foundSpecie = false;

        /** @var Specie $specie */
        foreach ($this->species as $specie) {
            if ($this->sameSpecies($child, $specie->genomes->offsetGet(0))) {
                $specie->genomes->add($child);
                $foundSpecie = true;
                break;
            }
        }

        if (!$foundSpecie) {
            $childSpecie = new Specie();
            $childSpecie->addGenome($child);
            $this->species->add($childSpecie);
        }
    }

    public function breedChild(Specie $specie)
    {
        if (lcg_value() < self::CROSSOVER_CHANCE) {
            $g1 = $specie->genomes->offsetGet(mt_rand(0, $specie->genomes->count()));
            $g2 = $specie->genomes->offsetGet(mt_rand(0, $specie->genomes->count()));
            $child = $this->mutation->crossOver($g1, $g2);
        } else {
            $g = $specie->genomes->offsetGet(mt_rand(0, $specie->genomes->count()));
            $child = $this->mutation->cloneEntity($g);
        }

        return $child;
    }

    public function createBasicGenome()
    {
        $genome = new Genome();
        $genome->setMaxNeuron($this->inputAggregator->count() + 1);
        $this->mutation->mutate($genome);

        return $genome;
    }

    public function cutSpecies($cutToOne)
    {
        /** @var Specie $specie */
        foreach ($this->species as $specie) {

            $iterator = $specie->getGenomes()->getIterator();
            $iterator->uasort(
                function ($first, $second) {
                    /** @var Genome $first */
                    /** @var Genome $second */
                    return $first->getFitness() > $second->getFitness() ? -1 : 1;
                }
            );

            $remaining = $cutToOne ? 1 : ceil($specie->getGenomes()->count() / 2);
            $remainingSpecie = array();
            while ($specie->getGenomes()->count() > $remaining) {
                $remainingSpecie = array_pop(iterator_to_array($iterator, true));
            }

            $specie->setGenomes($remainingSpecie);
        }
    }

    public function disjoint(Genome $g1, Genome $g2)
    {
        $disjointGenes = 0;

        $innovation1 = array();
        /** @var Gene $gene */
        foreach ($g1->getGenes() as $gene) {
            $innovation1[] = $gene->getInnovation();
        }

        $innovation2 = array();
        /** @var Gene $gene */
        foreach ($g2->getGenes() as $gene) {
            $innovation2[] = $gene->getInnovation();
        }

        foreach ($g1->getGenes() as $gene) {
            if (!in_array($gene->getInnovation(), $innovation2)) {
                $disjointGenes++;
            }
        }

        foreach ($g2->getGenes() as $gene) {
            if (!in_array($gene->getInnovation(), $innovation1)) {
                $disjointGenes++;
            }
        }

        $max = max($g1->getGenes()->count(), $g2->getGenes()->count());

        return $disjointGenes / $max;
    }

    /**
     * @return int
     */
    public function getCurrentGenome()
    {
        return $this->currentGenome;
    }

    /**
     * @return int
     */
    public function getCurrentSpecies()
    {
        return $this->currentSpecies;
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @return int
     */
    public function getGeneration()
    {
        return $this->generation;
    }

    /**
     * @return int
     */
    public function getInnovation()
    {
        return $this->innovation;
    }

    /**
     * @return InputsAggregator
     */
    public function getInputAggregator()
    {
        return $this->inputAggregator;
    }

    /**
     * @return int
     */
    public function getMaxFitness()
    {
        return $this->maxFitness;
    }

    /**
     * @return Mutation
     */
    public function getMutation()
    {
        return $this->mutation;
    }

    /**
     * @return ArrayCollection
     */
    public function getSpecies()
    {
        return $this->species;
    }

    public function newGeneration()
    {
        // Cull the bottom half of each species
        $this->cutSpecies(false);
        $this->rankGlobally();
        $this->removeStaleSpecies();
        $this->rankGlobally();

        /** @var Specie $specie */
        foreach ($this->species as $specie) {
            $specie->calculateAverageFitness();
        }

        $this->removeWeakSpecies();

        $sum = $this->totalAverageFitness();
        $children = new ArrayCollection();

        foreach ($this->species as $specie) {
            $breed = floor($specie->getAverageFitness() / $sum * self::POPULATION) - 1;

            for ($i = 0; $i < $breed; $i++) {
                $children->add($this->breedChild($specie));
            }
        }

        $this->cutSpecies(true);

        while ($children->count() + $this->species->count() < self::POPULATION) {
            $specie = $this->species->offsetGet(rand(0, $this->species->count()));
            $children->add($this->breedChild($specie));
        }

        /** @var Genome $child */
        foreach ($children as $child) {
            $this->addToSpecies($child);
        }

        $this->generation++;

        $this->em->flush();
    }

    public function newInnovation()
    {
        $this->innovation++;

        return $this->innovation;
    }

    public function rankGlobally()
    {
        $global = new ArrayCollection();


        /**
         * @var int    $key
         * @var Specie $specie
         */
        foreach ($this->species as $key => $specie) {
            foreach ($specie->getGenomes() as $genome) {
                $global->add($genome);
            }

        }

        $iterator = $global->getIterator();
        $iterator->uasort(
            function ($first, $second) {
                /** @var Genome $first */
                /** @var Genome $second */
                return $first->getFitness() < $second->getFitness() ? -1 : 1;
            }
        );

        /** @var Genome $genome */
        foreach ($iterator as $rank => $genome) {
            $genome->setGlobalRank($rank);
        }
    }

    public function removeSpecie(Specie $specie)
    {
        $this->species->removeElement($specie);
    }

    public function removeStaleSpecies()
    {
        $survived = new ArrayCollection();

        /**
         * @var int    $key
         * @var Specie $specie
         */
        foreach ($this->species as $key => $specie) {
            $iterator = $specie->getGenomes()->getIterator();
            $iterator->uasort(
                function ($first, $second) {
                    /** @var Genome $first */
                    /** @var Genome $second */
                    return $first->getFitness() > $second->getFitness() ? -1 : 1;
                }
            );

            if ($iterator->offsetGet(0)->getFitness() > $specie->getTopFitness()) {
                $specie->setTopFitness($iterator->offsetGet(0)->getFitness());
                $specie->setStaleness(0);
            } else {
                $specie->staleness++;
            }

            if ($specie->getStaleness() < self::STALE_SPECIES ||
                $specie->getTopFitness() >= $this->getMaxFitness()
            ) {
                $survived->add($specie);
            }
        }
        $this->setSpecies($survived);
    }

    public function removeWeakSpecies()
    {
        $survived = new ArrayCollection();
        $sum = $this->totalAverageFitness();

        /** @var Specie $specie */
        foreach ($this->species as $specie) {
            $breed = floor($specie->getAverageFitness() / $sum * self::POPULATION);
            if ($breed >= 1) {
                $survived->add($specie);
            }
        }

        $this->setSpecies($survived);
    }

    public function sameSpecies($genome1, $genome2)
    {
        $dd = self::DELTA_DISJOINT * $this->disjoint($genome1, $genome2);
        $dw = self::DELTA_WEIGHT * $this->weight($genome1, $genome2);

        return $dd + $dw < self::DELTA_THRESHOLD;
    }

    /**
     * @param int $currentGenome
     */
    public function setCurrentGenome($currentGenome)
    {
        $this->currentGenome = $currentGenome;
    }

    /**
     * @param int $currentSpecies
     */
    public function setCurrentSpecies($currentSpecies)
    {
        $this->currentSpecies = $currentSpecies;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @param int $generation
     */
    public function setGeneration($generation)
    {
        $this->generation = $generation;
    }

    /**
     * @param int $innovation
     */
    public function setInnovation($innovation)
    {
        $this->innovation = $innovation;
    }

    /**
     * @param InputsAggregator $inputAggregator
     */
    public function setInputAggregator($inputAggregator)
    {
        $this->inputAggregator = $inputAggregator;
    }

    /**
     * @param int $maxFitness
     */
    public function setMaxFitness($maxFitness)
    {
        $this->maxFitness = $maxFitness;
    }

    /**
     * @param Mutation $mutation
     */
    public function setMutation($mutation)
    {
        $this->mutation = $mutation;
    }

    /**
     * @param ArrayCollection $species
     */
    public function setSpecies($species)
    {
        $this->species = $species;
    }

    public function totalAverageFitness()
    {
        $total = 0;
        /** @var Specie $specie */
        foreach ($this->species as $specie) {
            $total += $specie->getAverageFitness();
        }

        return $total;
    }

    public function weight(Genome $g1, Genome $g2)
    {
        $innovation = array();

        /** @var Gene $gene */
        /** @var Gene $gene2 */
        foreach ($g2->getGenes() as $gene) {
            $innovation[$gene->getInnovation()] = $gene;
        }

        $sum = 0;
        $coincident = 0;

        foreach ($g1->getGenes() as $gene) {
            if (isset($innovation[$gene->getInnovation()])) {
                $gene2 = $innovation[$gene->getInnovation()];
                $sum += abs($gene->getWeight() - $gene2->getWeight());
                $coincident++;
            }
        }

        // on php7 a division by zero (forced) returns INF Or before that, it returned false.
        // if INF is always > to any number, false is not.
        return (@($sum/$coincident) === false) ? (($sum < 0) ? -INF : (($sum == 0) ? NAN : INF)) : @($sum/$coincident);
    }
}