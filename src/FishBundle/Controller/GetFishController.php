<?php

namespace FishBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class GetFishController extends Controller
{
    /**
     * @Route("/fish", name="fish")
     *
     * @throws \InvalidArgumentException
     * @throws ServiceCircularReferenceException
     * @throws ServiceNotFoundException
     */
    public function indexAction()
    {
        $repo = $this->container->get('fish.repository');
        $fish = $repo->findAliveFish();

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $response = new Response($serializer->serialize($fish, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
