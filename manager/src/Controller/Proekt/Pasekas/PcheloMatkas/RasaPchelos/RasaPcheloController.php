<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\PcheloMatkas\RasaPchelos;

//use App\ReadModel\Adminka\PcheloMatkas\PcheloMatka\CommentPcheloFetcher;
use App\ReadModel\Drevos\Rass\RasFetcher;
//use App\Model\Comment\UseCase\Comment;
use App\Controller\ErrorHandler;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("app/proekts/pasekas/pchelomatkas/rasa-pchelos", name="app.proekts.pasekas.pchelomatkas.rasa-pchelos")
 */

class RasaPcheloController extends AbstractController
{

    /**
     * @Route("/rasapchelo", name=".rasapchelo")
     * @param RasFetcher $fetcher
     * @return Response
     */
    public function rasapchelo(Request $request, RasFetcher $fetcher
                               ): Response
    {
       $rasas = $fetcher->all();


        return $this->render('app/proekts/pasekas/pchelomatkas/rasa-pchelos/rasapchelo.html.twig', [
            'rasas' => $rasas,
        ]);
    }
    /**
     * @Route("/infa", name=".infa")
     * @return Response
     */
    public function infa(): Response
    {

        return $this->render('/app/proekts/pasekas/pchelomatkas/rasa-pchelos/infa.html.twig' );
    }
}
