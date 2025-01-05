<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\Uchasties\Uchastiee;

use App\ReadModel\User\UserFetcher;
use App\ReadModel\Adminka\Uchasties\Uchastie\UchastieFetcher;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/app/proekts/pasekas/uchasties/uchastiee/infa", name="app.proekts.pasekas.uchasties.uchastiee.infa")
 */
class InformController extends AbstractController
{
 
	/**
     * @Route("/inform", name=".inform")
     * @return Response
     */
    public function inform(): Response
    {

        return $this->render('app/proekts/pasekas/uchasties/uchastiee/infa/inform.html.twig');
    }

	/**
     * @Route("/infas", name=".infas")
     * @return Response
     * @param UchastieFetcher $fetchers
     * @param UserFetcher $users
     */
    public function infas(UchastieFetcher $fetchers, UserFetcher $users): Response
    {
       
// dd($this->getUser());
        $user = $users->get($this->getUser()->getId());
        $last = $user->getName()->getLast();
        $fetcher = $fetchers->allNike();

        //$mestonomer = $mestonomers ->get(new Id($this->getUser()->getId()));

        $uchastie = $fetchers ->find($this->getUser()->getId());


        return $this->render('/app/proekts/pasekas/uchasties/uchastiee/infa/infas.html.twig',
                                compact('fetcher', 'uchastie', 'last'));
    }

}
