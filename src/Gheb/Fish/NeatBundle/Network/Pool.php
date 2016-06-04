<?php

namespace Gheb\Fish\NeatBundle\Network;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Gheb\Fish\IOBundle\Inputs\InputsAggregator;
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
    public $id;

    /**
     * @var int
     */
    public $currentGenome = 0;

    /**
     * @var int
     */
    public $currentSpecies = 0;

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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Add a specie to the pool
     * @param Specie $specie
     */
    public function addSpecie(Specie $specie)
    {
        $this->species->add($specie);
        $specie->setPool($this);
    }

    /**
     * Add a genome to a specie. If it does not belong to any existing specie according to it's weight and evolution number, create a new specie.
     * @param Genome $child
     */
    public function addToSpecies(Genome $child)
    {
        $foundSpecie = false;

        /** @var Specie $specie */
        foreach ($this->species as $specie) {
            if ($this->sameSpecies($child, $specie->genomes->offsetGet(0))) {
                $specie->addGenome($child);
                $foundSpecie = true;
                break;
            }
        }

        if (!$foundSpecie) {
            $childSpecie = new Specie();

            $childSpecie->addGenome($child);
            $this->addSpecie($childSpecie);
        }
    }

    /**
     * For a specie, has a chance X over 0.75 to crossover 2 random genomes and return it,
     * or to create a new genome based on a random existing one
     *
     *@param Specie $specie
     *
     * @return Genome
     */
    public function breedChild(Specie $specie)
    {
        if (lcg_value() < self::CROSSOVER_CHANCE) {
            $g1 = $specie->genomes->offsetGet(mt_rand(1, $specie->genomes->count())-1);
            $g2 = $specie->genomes->offsetGet(mt_rand(1, $specie->genomes->count())-1);
            $child = $this->mutation->crossOver($g1, $g2);
        } else {
            $g = $specie->genomes->offsetGet(mt_rand(1, $specie->genomes->count())-1);
            $child = $this->mutation->cloneEntity($g);
        }

        return $child;
    }

    /**
     * Create a Genome and set it's maxNeuron to the amount of inputs +1 and then applies a first mutation
     *
     * @use Mutation::mutate
     * @return Genome
     */
    public function createBasicGenome()
    {
        $genome = new Genome();

        $genome->setMaxNeuron($this->inputAggregator->count() + 1);
        $this->mutation->mutate($genome, $this);

        return $genome;
    }

    /**
     * Tries to get to the next genome. If we passed the number of genome available, we try a new specie.
     * If we passed the number of species available, create a new generation.
     */
    public function nextGenome()
    {
        $this->currentGenome++;

        if ($this->currentGenome > $this->species->offsetGet($this->currentSpecies)->getGenomes()->count()-1) {
            $this->currentGenome = 0;
            $this->currentSpecies++;
            if ($this->currentSpecies > $this->species->count()-1) {
                $this->newGeneration();
                $this->currentSpecies = 0;
            }
        }
    }

    /**
     * Remove the lower fitness half genomes of each specie or keep only the highest fitness genome of each specie.
     * @param bool $cutToOne
     */
    public function cullSpecies($cutToOne = false)
    {
        /** @var Specie $specie */
        foreach ($this->species as $specie) {

            $iterator = $specie->getGenomes()->getIterator();

            // order from lower to higher
            $iterator->uasort(
                function ($first, $second) {
                    /** @var Genome $first */
                    /** @var Genome $second */
                    return $first->getFitness() < $second->getFitness() ? -1 : 1;
                }
            );

            $remaining = $cutToOne ? 1 : ceil($specie->getGenomes()->count() / 2);
            $remainingGenomes = new ArrayCollection();
            $genomes = iterator_to_array($iterator, true);
            while (count($genomes) > $remaining) {
                // get the highest
                $remainingGenomes->add(array_pop($genomes));
            }

            $specie->setGenomes($remainingGenomes);
        }
    }

    /**
     * Calculate how far two genomes are different based on genes innovation number.
     * Each time a genome gene innovation is not found in the second genome genes innovation push the genome away from each other.
     *
     * @param Genome $g1
     * @param Genome $g2
     *
     * @return float
     */
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

    /**
     * Create a all new generation
     */
    public function newGeneration()
    {
        // Remove the lower fitness half genomes of each specie
        $this->cullSpecies(false);

        // give a rank based on it's fitness
        $this->rankGlobally();

        // Remove all species not having enough fitness for the pool previous maxfitness
        $this->removeStaleSpecies();

        // give a rank based on it's fitness
        $this->rankGlobally();

        /** @var Specie $specie */
        foreach ($this->species as $specie) {
            $specie->calculateAverageFitness();
        }

        // Remove all species having a fitness lower than the average
        $this->removeWeakSpecies();

        $sum = $this->totalAverageFitness();
        $children = new ArrayCollection();

        // for each specie, if it average fitness is higher than the global population,
        // it has a chance to create a new child
        foreach ($this->species as $specie) {
            $breed = floor($specie->getAverageFitness() / $sum * self::POPULATION) - 1;

            for ($i = 0; $i < $breed; $i++) {
                $children->add($this->breedChild($specie));
            }
        }

        // keep only the highest fitness genome of each specie
        $this->cullSpecies(true);

        // Since the creation of new child is based on top fitness species,
        // it does not contains as much population as the maximum defined.
        // Therefor we create a new child from a random specie until the max population is reached
        while ($children->count() + $this->species->count() < self::POPULATION) {
            $specie = $this->species->offsetGet(rand(0, $this->species->count()));
            $children->add($this->breedChild($specie));
        }

        /** @var Genome $child */
        // we re-dispatch the new children through all the existing species (or new thanks to mutations)
        foreach ($children as $child) {
            $this->addToSpecies($child);
        }

        $this->generation++;

        $this->em->flush();
    }

    /**
     * Up innovation number of 1 and returns it
     * @return int
     */
    public function newInnovation()
    {
        $this->innovation++;
        return $this->innovation;
    }

    /**
     * Higher is better
     */
    public function rankGlobally()
    {
        $global = new ArrayCollection();

        /**
         * @var Specie $specie
         */
        foreach ($this->species as $specie) {
            foreach ($specie->getGenomes() as $genome) {
                $global->add($genome);
            }
        }

        $iterator = $global->getIterator();
        // from lower to higher
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

        $this->em->flush();
    }

    public function removeSpecie(Specie $specie)
    {
        $specie->setPool(null);
        $this->species->removeElement($specie);
    }

    /**
     * Remove all species not having enough fitness for the pool previous maxfitness
     */
    public function removeStaleSpecies()
    {
        $survived = new ArrayCollection();

        /**
         * @var int    $key
         * @var Specie $specie
         */
        foreach ($this->species as $key => $specie) {
            $iterator = $specie->getGenomes()->getIterator();

            // from higher to lower
            $iterator->uasort(
                function ($first, $second) {
                    /** @var Genome $first */
                    /** @var Genome $second */
                    return $first->getFitness() > $second->getFitness() ? -1 : 1;
                }
            );

            // if the highest fitness is higher than specie fitness, replace it
            if ($iterator->offsetGet(0)->getFitness() > $specie->getTopFitness()) {
                $specie->setTopFitness($iterator->offsetGet(0)->getFitness());
                $specie->setStaleness(0);
            } else {
                $specie->staleness++;
            }

            // if the staleness is under the max or if the top fitness of the species overpasses the pool max fitness, then keep it.
            if ($specie->getStaleness() < self::STALE_SPECIES ||
                $specie->getTopFitness() >= $this->getMaxFitness()
            ) {
                $survived->add($specie);
            } else {
                $specie->setPool(null);
            }
        }
        $this->setSpecies($survived);
    }

    /**
     * Remove all species having a fitness lower than the average
     */
    public function removeWeakSpecies()
    {
        $survived = new ArrayCollection();
        $sum = $this->totalAverageFitness();

        /** @var Specie $specie */
        foreach ($this->species as $specie) {
            $breed = floor($specie->getAverageFitness() / $sum * self::POPULATION);
            if ($breed >= 1) {
                $survived->add($specie);
            } else {
                $specie->setPool(null);
            }
        }

        $this->setSpecies($survived);
    }

    /**
     * Return if two genome seems to be part of a same specie or not based on it's desjoint and weight.
     *
     * @param $genome1
     * @param $genome2
     *
     * @return bool
     */
    public function sameSpecies($genome1, $genome2)
    {
        $dd = self::DELTA_DISJOINT * $this->disjoint($genome1, $genome2);
        $dw = self::DELTA_WEIGHT * $this->weight($genome1, $genome2);

        $add = $dd + $dw;

        return is_nan($add) ? true : ($add < self::DELTA_THRESHOLD);
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
     * @param Aggregator $inputAggregator
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

    /**
     * Return the sum of species average fitness
     * @return int
     */
    public function totalAverageFitness()
    {
        $total = 0;
        /** @var Specie $specie */
        foreach ($this->species as $specie) {
            $total += $specie->getAverageFitness();
        }

        return $total;
    }

    /**
     * Return the weight difference between two genomes
     * @param Genome $g1
     * @param Genome $g2
     *
     * @return float
     */
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