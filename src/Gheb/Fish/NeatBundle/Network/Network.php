<?php

namespace Gheb\Fish\NeatBundle\Network;


use Gheb\Fish\IOBundle\Inputs\InputsAggregator;
use Gheb\Fish\IOBundle\Outputs\OutputsAggregator;
use Gheb\Fish\NeatBundle\Network\Gene;
use Gheb\Fish\NeatBundle\Network\Genome;

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
        for ($i = 0; $i < $inputsAggregator->count(); $i++) {
            $neuron = new Neuron();
            $neuron->setPosition($i);
            $genome->addNeuron($neuron);
        }

        for ($j = 0; $j < $outputsAggregator->count(); $j++) {
            $neuron = new Neuron();
            $neuron->setPosition(self::MAX_NODES + $j);
            $genome->addNeuron($neuron);
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
                if (null == $genome->getNeuron($gene->getOut())) {
                    $neuron = new Neuron();
                    $neuron->setPosition($gene->getOut());
                    $genome->addNeuron($neuron);
                }

                /** @var Neuron $neuron */
                $neuron = $genome->getNeuron($gene->getOut());
                $neuron->incoming->add($gene);

                if (null == $genome->getNeuron($gene->getInto())) {
                    $neuron = new Neuron();
                    $neuron->setPosition($gene->getInto());
                    $genome->addNeuron($neuron);
                }
            }
        }
    }

    /**
     * Receive inputs and evaluate them in function of their values
     *
     * @param Genome $genome
     * @param $inputs
     * @param OutputsAggregator $outputsAggregator
     * @param InputsAggregator $inputsAggregator
     *
     * @return array|void
     * @throws \Exception
     */
    public static function evaluate(Genome $genome, $inputs, OutputsAggregator $outputsAggregator, InputsAggregator $inputsAggregator)
    {
        if ($inputsAggregator->count() != count($inputs)) {
            throw new \Exception('Incorrect number of neural network inputs');
        }

        for ($i = 0; $i < $inputsAggregator->count(); $i++) {
            $genome->getNeuron($i)->setValue($inputs[$i]->getValue());
        }

        /** @var Neuron $neuron */
        foreach ($genome->getNetwork() as $neuron) {
            $sum = 0;
            /** @var Gene $incoming */
            foreach ($neuron->getIncoming() as $incoming) {
                /** @var Neuron $other */
                $other = $genome->getNeuron($incoming->getInto());
                $sum += $incoming->getWeight() * $other->getValue();
            }

            if ($neuron->getIncoming()->count() > 0) {
                $neuron->setValue(self::sigmoid($sum));
            }
        }

        $triggeredOutputs = array();
        for ($j = 0; $j < $outputsAggregator->count(); $j++) {
            if ($genome->getNeuron(self::MAX_NODES + $j)->getValue() > 0) {
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
    public static function sigmoid($x){
	    return 2/(1+exp(-4.9*$x))-1;
    }
}