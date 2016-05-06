<?php

namespace Gheb\Fish\IOBundle;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Gheb\Fish\IOBundle\Inputs\AbstractInput;
use Gheb\Fish\IOBundle\Monolog\IOLogger;
use Gheb\Fish\IOBundle\Outputs\AbstractOutput;
use Gheb\Fish\IOBundle\Outputs\OutputsAggregator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Validator\Tests\Fixtures\Entity;

/**
 * Class Aggregator
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle
 */
class Aggregator
{
    /**
     * @var ArrayCollection
     */
    public $aggregate;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var IOLogger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $parentClass = '';

    /**
     * Aggregator constructor.
     *
     * @param EntityManager $em
     * @param IOLogger      $logger
     */
    public function __construct(EntityManager $em, IOLogger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->aggregate = new ArrayCollection();
    }

    /**
     * Aggregate all input/output in one place
     *
     * @throws \Exception
     */
    public function aggregate()
    {
        $this->parentClass = self::guessParent();

        if (trim($this->parentClass) == '') {
            throw new \Exception('A parent class must be defined. Impossible to find an abstract class.');
        }

        $finder = new Finder();
        $iterator = $finder->files()->in($this->getDir());

        $ns = $this->getNS();

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {

            if ($relativePath = $file->getRelativePath()) {
                $ns .= '\\'.strtr($relativePath, '/', '\\');
            }

            $class  = $ns.'\\'.$file->getBasename('.php');
            $r      = new \ReflectionClass($class);

            if (!$r->isAbstract() && $r->getParentClass() && $r->getParentClass()->getName() == $this->parentClass) {
                $this->aggregate->add($r->newInstance($this->em, $this->logger));
            }
        }
    }

    /**
     * Return a aggregated input/output according to it's name
     *
     * @param $name
     *
     * @return AbstractOutput|AbstractInput
     */
    public function getAggregated($name)
    {
        $aggregated = $this->aggregate->filter(
            function ($aggregated) use ($name) {
                /** @var AbstractOutput|AbstractInput $aggregated */
                return strtolower($aggregated->getName()) == strtolower($name);
            }
        );


        return $aggregated->first();
    }

    /**
     * return current dir for children
     * @return string
     */
    protected function getDir()
    {
        $reflector = new \ReflectionClass(get_class($this));
        $filename = $reflector->getFileName();

        return dirname($filename);
    }

    /**
     * return current namespace for children
     * @return string
     */
    protected function getNS()
    {
        $reflector = new \ReflectionClass(get_class($this));
        $ns = $reflector->getNamespaceName();

        return $ns;
    }

    /**
     * Find the first abstract file within the directory
     * @return string
     */
    private function guessParent()
    {
        $finder = new Finder();
        $iterator = $finder->files()->in($this->getDir());

        $ns = $this->getNS();

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {

            if ($relativePath = $file->getRelativePath()) {
                $ns .= '\\'.strtr($relativePath, '/', '\\');
            }

            $class  = $ns.'\\'.$file->getBasename('.php');
            $r      = new \ReflectionClass($class);

            if ($r->isAbstract()) {
                return $ns.'\\'.$file->getBasename('.php');
            }
        }
    }
}