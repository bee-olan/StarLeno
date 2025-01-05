<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\ChildPchelos;

use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Executor;
use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Plan;

//use App\ReadModel\Proekt\Pasekas\ChildPchelos\Side\Filter;
//use App\ReadModel\Proekt\Pasekas\ChildPchelos\Side\ChildSideFetcher;

use App\ReadModel\Adminka\PcheloMatkas\ChildPchelo\Filter;
use App\ReadModel\Adminka\PcheloMatkas\ChildPchelo\ChildPcheloFetcher;
use App\Controller\ErrorHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("app/proekts/pasekas/childpchelos", name="app.proekts.pasekas.childpchelos")
 * @ParamConverter("pchelomatka", options={"id" = "pchelomatka_id"})
 */
class ChildMeeOwnController extends AbstractController
{
    private const PER_PAGE = 50;

    private $errors;

    public function __construct( ErrorHandler $errors)
    {
        $this->errors = $errors;
    }


    /**
     * @Route("/me", name=".me")
     * @ParamConverter("pchelomatka", options={"id" = "pchelomatka_id"})
     * @param Request $request
     * @param ChildPcheloFetcher $childpchelos
     * @return Response
     */
    public function me(Request $request, ChildPcheloFetcher $childpchelos): Response
    {
        $filter = Filter\Filter::alll();

        $form = $this->createForm(Filter\Form::class, $filter, [
            'action' => $this->generateUrl('app.proekts.pasekas.childpchelos'),
        ]);

        $form->handleRequest($request);

        $pagination = $childpchelos->alll(
            $filter->forExecutor($this->getUser()->getId()),
            $request->query->getInt('page', 1),
            self::PER_PAGE,
//            $request->query->get('sort'),
//            $request->query->get('direction')
            $request->query->get('sort'),
            $request->query->get('direction')
        );

        return $this->render('app/proekts/pasekas/childpchelos/index.html.twig', [
            'pchelomatka' => null,
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/own", name=".own")
     * @ParamConverter("pchelomatka", options={"id" = "pchelomatka_id"})
     * @param Request $request
     * @param ChildPcheloFetcher $childpchelos
     * @return Response
     */
    public function own(Request $request, ChildPcheloFetcher $childpchelos): Response
    {
        $filter = Filter\Filter::alll();

        $form = $this->createForm(Filter\Form::class, $filter, [
            'action' => $this->generateUrl('app.proekts.pasekas.childpchelos'),
        ]);

        $form->handleRequest($request);

        $pagination = $childpchelos->alll(
            $filter->forAuthor($this->getUser()->getId()),
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort'),
            $request->query->get('direction')
        );

        return $this->render('app/proekts/pasekas/childpchelos/index.html.twig', [
            'pchelomatka' => null,
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

}


