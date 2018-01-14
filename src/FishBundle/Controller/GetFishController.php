<?php

namespace FishBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use FishBundle\Entity\Fish;
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
     * @throws ORMInvalidArgumentException
     * @throws OptimisticLockException
     */
    public function indexAction()
    {
        $repo = $this->container->get('fish.repository');
        $fish = $repo->findAliveFish();

        if (null === $fish) {
            $fish = new Fish();

            /** @var EntityManager $em */
            $em = $this->container->get('doctrine.orm.entity_manager');
            $em->persist($fish);
            $em->flush();
        }

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $response = new Response($serializer->serialize($fish, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
