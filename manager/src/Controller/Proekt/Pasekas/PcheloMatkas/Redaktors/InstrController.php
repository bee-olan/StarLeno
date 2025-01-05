<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\PcheloMatkas\Redaktors;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/app/proekts/pasekas/pchelomatkas/pchelomatka/redaktors", name="app.proekts.pasekas.pchelomatkas.pchelomatka.redaktors")
 */
class InstrController extends AbstractController
{

    /**
     * @Route("/instr", name=".instr")
     * @return Response
     */
    public function instr(): Response
    {
        return $this->render('app/proekts/pasekas/pchelomatkas/pchelomatka/redaktors/instr.html.twig');
    }

 
}