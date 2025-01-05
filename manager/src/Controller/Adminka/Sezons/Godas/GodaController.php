<?php

declare(strict_types=1);

namespace App\Controller\Adminka\Sezons\Godas;

use App\Controller\ErrorHandler;

use App\Model\Adminka\UseCase\Sezons\Godas\Create;
use App\ReadModel\Adminka\Sezons\Godas\GodaFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/adminka/sezons/godas", name="adminka.sezons.godas")
 */
class GodaController extends AbstractController
{
    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("", name="")
     * @param GodaFetcher $fetcher
     * @return Response
     */
    public function index(GodaFetcher $fetcher): Response
    {
        $godass = $fetcher->all();

        return $this->render(
            'app/adminka/sezons/godas/index.html.twig',
            compact('godass')
        );
    }

    /**
     * @Route("/ind-pr", name=".ind-pr")
     * @param GodaFetcher $fetcher
     * @return Response
     */
    public function indPr(GodaFetcher $fetcher): Response
    {
        $godass = $fetcher->all();
        //dd($rasas);

        return $this->render(
            'app/adminka/sezons/godas/ind-pr.html.twig',
            compact('godass')
        );
    }

    /**
     * @Route("/create", name=".create")
     * @param GodaFetcher $godas
     * @param Request $request
     * @param Create\Handler $handler
     * @return Response
     */
    public function create(Request $request, GodaFetcher $godas, Create\Handler $handler): Response
    {
        $godMax = (int)($godas->getMaxGod() + 1);

        if ($godMax < 2007) {
            $godMax = 2008;
        }

        $command = new Create\Command($godMax);

        try {
            $handler->handle($command);
            return $this->redirectToRoute('adminka.sezons.godas');
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }
        return $this->render('adminka/sezons/godas/create.html.twig');
    }


    /**
     * @Route("/show", name=".show")
     * @return Response
     */
    public function show(): Response
    {
        return $this->redirectToRoute('adminka.sezons.godas');
    }


}
