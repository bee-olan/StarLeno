<?php

declare(strict_types=1);

namespace App\Controller\Proekt\PageGlavas\PcheloMatkas;

//use App\Model\Adminka\Entity\Uchasties\Personas\PersonaRepository;
//use App\Model\Adminka\Entity\Uchasties\Personas\Id as PersonaId;
//use App\Model\Adminka\Entity\Uchasties\Uchastie\Id;
//use App\Model\Adminka\Entity\Uchasties\Uchastie\UchastieRepository;
//use App\Model\Mesto\Entity\InfaMesto\MestoNomerRepository;
//use App\Model\Mesto\Entity\InfaMesto\Id as MestoNomerId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("app/proekts/page_glavas/pchelomatka/childs", name="app.proekts.page_glavas.pchelomatka.childs")
 */

class ChildMenuController extends AbstractController
{
//    /**
//     * @Route("", name="")
//     * @param Request $request
//     * @param MestoNomerRepository $mestoNomers
//     * @param UchastieRepository $uchasties
//     * @param PersonaRepository $personas
//     * @return Response
//     */
//    public function index(Request $request,
//                          UchastieRepository $uchasties,
//                          PersonaRepository $personas,
//                          MestoNomerRepository $mestoNomers
//                            ): Response
//    {
//        $idUser = $this->getUser()->getId();
//
//        $uchastie = $uchasties->has(new Id($idUser));
//        $persona = $personas->has(new PersonaId($idUser));
//        $mestoNomer = $mestoNomers->has(new MestoNomerId($idUser));
////        dd($mestoNomer);
//
//        return $this->render('/app/proekts/page_glavas/pchelomatka/childs/index.html.twig',
//         compact('uchastie', 'persona', 'mestoNomer')
//        );
//    }

    /**
     * @Route("", name="")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('app/proekts/page_glavas/pchelomatka/childs/index.html.twig');
    }

    /**
     * @Route("/show", name=".show")
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('app/proekts/page_glavas/pchelomatka/childs/show.html.twig');
    }

    /**
     * @Route("/reg-infa", name=".reg-infa")
     * @return Response
     */
    public function reginfa(): Response
    {
        return $this->render('app/proekts/page_glavas/pchelomatka/childs/reg-infa.html.twig');
    }

    /**
     * @Route("/spisok-instr", name=".spisok-instr")
     * @return Response
     */
    public function spisok(): Response
    {
        return $this->render('app/proekts/page_glavas/pchelomatka/childs/spisok-instr.html.twig');
    }

    /**
     * @Route("/lichnaja", name=".lichnaja")
     * @return Response
     */
    public function lichnaja(): Response
    {
        return $this->render('app/proekts/page_glavas/pchelomatka/childs/lichnaja.html.twig');
    }
}