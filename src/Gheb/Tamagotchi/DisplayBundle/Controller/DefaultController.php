<?php

namespace Gheb\Tamagotchi\DisplayBundle\Controller;

use Gheb\Tamagotchi\CoreBundle\Entity\Fish;
use Gheb\Tamagotchi\CoreBundle\Personality\PersonalityLoader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $character = $em->createQuery('select u from Gheb\Tamagotchi\CoreBundle\Entity\Fish u where u.health > 0')
                         ->getResult();

        if (empty($character)) {
            $personality = PersonalityLoader::load('average');
            $character = new Fish();

            $character->setPersonality($personality);

            $em->persist($character);
            $em->flush();
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/howIsMyFish", name="howismyfish")
     */
    public function howIsMyFishAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        /** @var Fish $character */
        $characters =$em->createQuery('select u from Gheb\Tamagotchi\CoreBundle\Entity\Fish u where u.health > 0')
            ->getResult();

        $character = array_pop($characters);

        return new Response(json_encode(array(
            'Mood' => $character->getMood(),
            'Health' => $character->getHealth(),
            'Happiness' => $character->getHappiness(),
            'Cleanliness' => $character->getCleanliness(),
            'Sleepful' => $character->getSleepFul(),
            'Hunger' => $character->getHunger()
        )));
    }
}
