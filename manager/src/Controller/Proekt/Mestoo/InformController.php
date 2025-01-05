<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Mestoo;

use App\ReadModel\User\UserFetcher;
use App\ReadModel\Mesto\InfaMesto\MestoNomerFetcher;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/app/proekts/mestos/infa", name="app.proekts.mestos.infa")
 */
class InformController extends AbstractController
{
 
	/**
     * @Route("/inform", name=".inform")
     * @return Response
     */
    public function inform(): Response
    {

        return $this->render('app/proekts/mestos/infa/inform.html.twig');
    }
	/**
     * @Route("/infas", name=".infas")
     * @return Response
     * @param MestoNomerFetcher $fetchers
     * @param UserFetcher $users
     */
    public function infas(MestoNomerFetcher $fetchers, UserFetcher $users): Response
    {
       
// dd($this->getUser());
        $user = $users->get($this->getUser()->getId());
        $last = $user->getName()->getLast();
        $fetcher = $fetchers->allMestNom();

        //$mestonomer = $mestonomers ->get(new Id($this->getUser()->getId()));

        $mestonomer = $fetchers ->find($this->getUser()->getId());


        return $this->render('/app/proekts/mestos/infa/infas.html.twig',
                                compact('fetcher', 'mestonomer', 'last'));
    }

}
