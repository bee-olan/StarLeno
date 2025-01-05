<?php

declare(strict_types=1);

namespace App\Controller\Proekt\PageGlavas\PcheloMatkas;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("app/proekts/page_glavas/pchelomatka", name="app.proekts.page_glavas.pchelomatka")
 */
class GlavMenuController extends AbstractController
{

       /**
     * @Route("", name="")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('app/proekts/page_glavas/pchelomatka/index.html.twig');
    }

    /**
     * @Route("/show", name=".show")
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('app/proekts/page_glavas/pchelomatka/show.html.twig');
    }

    /**
     * @Route("/spisok-instr", name=".spisok-instr")
     * @return Response
     */
    public function spisok(): Response
    {
        return $this->render('app/proekts/page_glavas/pchelomatka/spisok-instr.html.twig');
    }

    /**
     * @Route("/reg-infa", name=".reg-infa")
     * @return Response
     */
    public function reginfa(): Response
    {
        return $this->render('app/proekts/page_glavas/pchelomatka/reg-infa.html.twig');
    }
}