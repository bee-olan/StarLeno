<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\Uchasties;

use App\Annotation\RequiresUserCredits;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app/proekt/pasekas/uchasties", name="app.proekts.pasekas.uchasties")
 */
class BaseProController extends AbstractController
{
    /**
     * @Route("/basepro", name=".basepro")
     * @return Response
     */
    public function basepro(): Response
    {
        return $this->render('app/proekts/pasekas/uchasties/basepro.html.twig');
    }
}