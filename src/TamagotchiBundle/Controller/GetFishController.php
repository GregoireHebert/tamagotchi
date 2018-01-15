<?php

namespace TamagotchiBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use TamagotchiBundle\Entity\Tamagotchi;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class GetTamagotchiController extends Controller
{
    /**
     * @Route("/tamagotchi", name="tamagotchi")
     *
     * @throws \InvalidArgumentException
     * @throws ServiceCircularReferenceException
     * @throws ServiceNotFoundException
     * @throws ORMInvalidArgumentException
     * @throws OptimisticLockException
     */
    public function indexAction()
    {
        $repo = $this->container->get('tamagotchi.repository');
        $tamagotchi = $repo->findAliveTamagotchi();

        if (null === $tamagotchi) {
            $tamagotchi = new Tamagotchi();

            /** @var EntityManager $em */
            $em = $this->container->get('doctrine.orm.entity_manager');
            $em->persist($tamagotchi);
            $em->flush();
        }

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $response = new Response($serializer->serialize($tamagotchi, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
