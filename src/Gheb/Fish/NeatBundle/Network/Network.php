<?php

namespace Gheb\Fish\NeatBundle\Network;


use Gheb\Fish\IOBundle\Inputs\InputsAggregator;
use Gheb\Fish\IOBundle\Outputs\OutputsAggregator;
use Gheb\Fish\NeatBundle\Genomes\Gene;
use Gheb\Fish\NeatBundle\Genomes\Genome;

class Network
{
    const MAX_NODES = 1000000;

    /**
     * Structure a network of neurons based on genes in and out
     * @param Genome $genome
     * @param OutputsAggregator $outputsAggregator
     * @param InputsAggregator $inputsAggregator
     */
    public static function generateNetwork(Genome $genome, OutputsAggregator $outputsAggregator, InputsAggregator $inputsAggregator)
    {
        $neurons = array();

        for ($i = 0; $i < $inputsAggregator->count(); $i++) {
            $neurons[$i] = new Neuron();
        }

        for ($j = 0; $j < $outputsAggregator->count(); $j++) {
            $neurons[self::MAX_NODES + $j] = new Neuron();
        }

        // from lower to higher
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
                if (!isset($neurons[$gene->getOut()])) {
                    $neurons[$gene->getOut()] = new Neuron();
                }

                /** @var Neuron $neuron */
                $neuron = $neurons[$gene->getOut()];
                $neuron->incoming->add($gene);

                if (!isset($neurons[$gene->getInto()])) {
                    $neurons[$gene->getInto()] = new Neuron();
                }
            }
        }

        $genome->setNetwork($neurons);
    }

    /**
     * Receive inputs and evaluate them in function of their values
     *
     * @param array network
     * @param $inputs
     * @param OutputsAggregator $outputsAggregator
     * @param InputsAggregator $inputsAggregator
     *
     * @return array|void
     * @throws \Exception
     */
    public static function evaluate(Array $network, $inputs, OutputsAggregator $outputsAggregator, InputsAggregator $inputsAggregator)
    {
        if ($inputsAggregator->count() != count($inputs)) {
            throw new \Exception('Incorrect number of neural network inputs');
        }

        for ($i = 0; $i < $inputsAggregator->count(); $i++) {
            $network[$i]->setValue($inputs[$i]->getValue());
        }

        /** @var Neuron $neuron */
        foreach ($network as $neuron) {
            $sum = 0;
            /** @var Gene $incoming */
            foreach ($neuron->getIncoming() as $incoming) {
                /** @var Neuron $other */
                $other = $network[$incoming->getInto()];
                $sum += $incoming->getWeight() * $other->getValue();
            }

            if ($neuron->getIncoming()->count() > 0) {
                $neuron->setValue(self::sigmoid($sum));
            }
        }

        $triggeredOutputs = array();
        for ($j = 0; $j < $outputsAggregator->count(); $j++) {
            if ($network[self::MAX_NODES + $j]->getValue() > 0) {
                $triggeredOutputs[] = $outputsAggregator->aggregate->offsetGet($j);
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