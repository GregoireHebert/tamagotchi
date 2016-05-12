<?php

namespace Gheb\Fish\NeatBundle\Network;


use Gheb\Fish\IOBundle\Inputs\InputsAggregator;
use Gheb\Fish\IOBundle\Outputs\OutputsAggregator;
use Gheb\Fish\NeatBundle\Genomes\Gene;
use Gheb\Fish\NeatBundle\Genomes\Genome;

class Network
{
    const MAX_NODES = 1000000;
    public $neurons = array();

    /**
     * @var OutputsAggregator
     */
    private $outputsAggregator;

    /**
     * @var InputsAggregator
     */
    private $inputsAggregator;

    /**
     * @param Genome $genome
     * @param OutputsAggregator $outputsAggregator
     * @param InputsAggregator $inputsAggregator
     */
    public function _construct(Genome $genome, OutputsAggregator $outputsAggregator, InputsAggregator $inputsAggregator)
    {
        $this->outputsAggregator = $outputsAggregator;
        $this->inputsAggregator = $inputsAggregator;

        for ($i = 0; $i < $this->inputsAggregator->count(); $i++) {
            $this->neurons[$i] = new Neuron();
        }

        for ($j = 0; $j < $this->outputsAggregator->count(); $j++) {
            $this->neurons[self::MAX_NODES + $j] = new Neuron();
        }

        $iterator = $genome->getGenes()->getIterator();
        $iterator->uasort(
            function ($first, $second) {
                /** @var Gene $first */
                /** @var Gene $second */
                return $first->getOut() < $second->getOut() ? -1 : 1;
            }
        );

        for ($i = 0; $i < $iterator->count(); $i++) {
            /** @var Gene $gene */
            $gene = $iterator->offsetGet($i);

            if ($gene->isEnabled()) {
                if (!isset($this->neurons[$gene->getOut()])) {
                    $this->neurons[$gene->getOut()] = new Neuron();
                }

                /** @var Neuron $neuron */
                $neuron = $this->neurons[$gene->getOut()];
                $neuron->incoming->add($gene);

                if (!isset($this->neurons[$gene->getInto()])) {
                    $this->neurons[$gene->getInto()] = new Neuron();
                }
            }
        }

        $genome->setNetwork($this);
    }

    public function evaluate($inputs)
    {
        if ($this->inputsAggregator->count() != count($inputs)) {
            throw new \Exception('Incorrect number of neural network inputs');

            return;
        }

        for ($i = 0; $i < $this->inputsAggregator->count(); $i++) {
            $this->neurons[$i]->setValue($inputs[$i]->getValue());
        }

        /** @var Neuron $neuron */
        foreach ($this->neurons as $neuron) {
            $sum = 0;
            /** @var Gene $incoming */
            foreach ($neuron->getIncoming() as $incoming) {
                /** @var Neuron $other */
                $other = $this->neurons[$incoming->getInto()];
                $sum += $incoming->getWeight() * $other->getValue();
            }

            if ($neuron->getIncoming()->count() > 0) {
                $neuron->setValue($this->sigmoid($sum));
            }
        }

        $triggeredOutputs = array();
        for ($j = 0; $j < $this->outputsAggregator->count(); $j++) {
            if ($this->neurons[self::MAX_NODES + $j]->getValue() > 0) {
                $triggeredOutputs[] = $this->outputsAggregator->aggregate->offsetGet($j);
            }
        }

        return $triggeredOutputs;
    }

    /**
     * return sigmoidal result
     * @param $x
     * @return float
     */
    public function sigmoid($x){
	    return 2/(1+exp(-4.9*$x))-1;
    }
}