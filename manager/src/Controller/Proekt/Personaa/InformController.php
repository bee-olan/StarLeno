<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Personaa;

use App\ReadModel\Adminka\Uchasties\PersonaFetcher;
use App\ReadModel\User\UserFetcher;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/app/proekts/personaa/infa", name="app.proekts.personaa.infa")
 */
class InformController extends AbstractController
{
  

	/**
     * @Route("/inform", name=".inform")
     * @return Response
     */
    public function inform(): Response
    {

        return $this->render('app/proekts/personaa/infa/inform.html.twig');
    }

    /**
     * @Route("/infas", name=".infas")
     * @return Response
     * @return UserFetcher $users
     * @param PersonaFetcher $uchasties
     */
    public function infas(PersonaFetcher $uchasties, UserFetcher $users): Response
    {
       
        $user = $users->get($this->getUser()->getId());
        $last = $user->getName()->getLast();

        $personas = $uchasties->allPers();

        $personanom = $uchasties ->find($this->getUser()->getId());


        return $this->render('app/proekts/personaa/infa/infas.html.twig',
                                compact('personas', 'personanom', 'last'));
    }
}
