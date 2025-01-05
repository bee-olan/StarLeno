<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\ChildPchelos;

use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Executor;
use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Plan;
use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Start;
use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Take;
use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\TakeAndStart;
//use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
//use App\ReadModel\Proekt\Pasekas\ChildPchelo\Side\Filter;
//use App\ReadModel\Proekt\Pasekas\ChildPchelo\Side\ChildSideFetcher;

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
class ChildTakeStartController extends AbstractController
{
    private const PER_PAGE = 50;

    private $errors;

    public function __construct( ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("/{id}/take", name=".take", methods={"POST"})
     * @param ChildPchelo $childpchelo
     * @param Request $request
     * @param Take\Handler $handler
     * @return Response
     */
    public function take(ChildPchelo $childpchelo, Request $request, Take\Handler $handler): Response
    {
//        dd($childpchelo);
        if (!$this->isCsrfTokenValid('take', $request->request->get('token'))) {
            return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
        }

        // $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);

        $command = new Take\Command($this->getUser()->getId(), $childpchelo->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
    }

    /**
     * @Route("/{id}/take/start", name=".take_and_start", methods={"POST"})
     * @param ChildPchelo $childpchelo
     * @param Request $request
     * @param TakeAndStart\Handler $handler
     * @return Response
     */
    public function takeAndStart(ChildPchelo $childpchelo, Request $request, TakeAndStart\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('take-and-start', $request->request->get('token'))) {
            return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
        }

        // $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);

        $command = new TakeAndStart\Command($this->getUser()->getId(), $childpchelo->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
    }

    /**
     * @Route("/{id}/start", name=".start", methods={"POST"})
     * @param ChildPchelo $childpchelo
     * @param Request $request
     * @param Start\Handler $handler
     * @return Response
     */
    public function start(ChildPchelo $childpchelo, Request $request, Start\Handler $handler): Response
    {

        if (!$this->isCsrfTokenValid('start', $request->request->get('token'))) {
            return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
        }

        // $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);

        $command = new Start\Command($this->getUser()->getId(), $childpchelo->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
    }

}

