<?php

namespace Gheb\Fish\IOBundle;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class Aggregator
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle
 */
class Aggregator
{
    /**
     * @var array
     */
    public $outputs = array();
    protected $parentClass = '';

    private function guessParent()
    {
        $finder = new Finder();
        $iterator = $finder->files()->in(__DIR__);

        $ns = __NAMESPACE__;

        /** @var File $file */
        foreach ($iterator as $file) {

            if ($relativePath = $file->getPath()) {
                $ns .= '\\'.strtr($relativePath, '/', '\\');
            }

            $class  = $ns.'\\'.$file->getBasename('.php');
            $r      = new \ReflectionClass($class);

            if ($r->isAbstract()) {
                return $ns.'\\'.$file->getBasename();
            }
        }
    }

    public function aggregate()
    {
        $this->parentClass = $this->guessParent();

        if (trim($this->parentClass) == '') {
            throw new \Exception('A parent class must be defined. Impossible to find an abstract class.');
        }

        $finder = new Finder();
        $iterator = $finder->files()->in(__DIR__);

        $ns = __NAMESPACE__;

        /** @var File $file */
        foreach ($iterator as $file) {

            if ($relativePath = $file->getPath()) {
                $ns .= '\\'.strtr($relativePath, '/', '\\');
            }

            $class  = $ns.'\\'.$file->getBasename('.php');
            $r      = new \ReflectionClass($class);

            if (!$r->isAbstract() && $r->getParentClass() == $this->parentClass) {
                $this->outputs[] = $r->newInstance();
            }
        }
    }
}