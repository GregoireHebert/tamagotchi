<?php

namespace Gheb\Tamagotchi\DisplayBundle\Controller;

use Gheb\Tamagotchi\CoreBundle\Entity\Fish;
use Gheb\Tamagotchi\CoreBundle\Inputs\SupplyService;
use Gheb\Tamagotchi\CoreBundle\Personality\PersonalityLoader;
use Gheb\Tamagotchi\LifeBundle\Services\LifeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    protected $container;

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
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
        sleep(5);
        $lifeService   = $this->container->get('gheb.tamagotchi.lifeService');
        $lifeService->lifeIsUnfair();

        $em = $this->getDoctrine()->getManager();

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

    /**
     * @Route("/feedTheFish", name="feedthefish")
     */
    public function feedTheFishAction(Request $request)
    {
        try {
            /** @var SupplyService $supplyService */
            /** @var LifeService $lifeService */
            $supplyService = $this->container->get('gheb.tamagotchi.supplyService');
            $lifeService   = $this->container->get('gheb.tamagotchi.lifeService');
            $supplyService->feed();
            $lifeService->lifeIsUnfair();

            return new Response(json_encode(array('status'=>true)));
        } catch (\Exception $e) {
            return new Response(json_encode(array('status'=>false, 'error'=>$e->getMessage())));
        }
    }

    /**
     * @Route("/goFish", name="gofish")
     */
    public function goFishAction(Request $request)
    {
        /** @var SupplyService $supplyService */
        /** @var LifeService $lifeService */
        try {
            $supplyService = $this->container->get('gheb.tamagotchi.supplyService');
            $lifeService   = $this->container->get('gheb.tamagotchi.lifeService');
            $supplyService->play();
            $lifeService->lifeIsUnfair();

            return new Response(json_encode(array('status'=>true)));
        } catch (\Exception $e) {
            return new Response(json_encode(array('status'=>false, 'error'=>$e->getMessage())));
        }
    }

    /**
     * @Route("/inTheDarknessBindThem", name="inthedarknessbindthem")
     */
    public function inTheDarknessBindThemAction(Request $request)
    {
        /** @var SupplyService $supplyService */
        /** @var LifeService $lifeService */
        try {
            $supplyService = $this->container->get('gheb.tamagotchi.supplyService');
            $lifeService   = $this->container->get('gheb.tamagotchi.lifeService');
            $supplyService->turnOffLight();
            $lifeService->lifeIsUnfair();

            return new Response(json_encode(array('status'=>true)));
        } catch (\Exception $e) {
            return new Response(json_encode(array('status'=>false, 'error'=>$e->getMessage())));
        }
    }

    /**
     * @Route("/itsLupus", name="itslupus")
     */
    public function itsLupusAction(Request $request)
    {
        /** @var SupplyService $supplyService */
        /** @var LifeService $lifeService */
        try {
            $supplyService = $this->container->get('gheb.tamagotchi.supplyService');
            $lifeService   = $this->container->get('gheb.tamagotchi.lifeService');
            $supplyService->heal();
            $lifeService->lifeIsUnfair();

            return new Response(json_encode(array('status'=>true)));
        } catch (\Exception $e) {
            return new Response(json_encode(array('status'=>false, 'error'=>$e->getMessage())));
        }
    }
}
