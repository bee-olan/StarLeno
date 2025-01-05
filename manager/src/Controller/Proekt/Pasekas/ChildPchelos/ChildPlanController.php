<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\ChildPchelos;

use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Executor;

use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Plan;

use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
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
class ChildPlanController extends AbstractController
{
    private const PER_PAGE = 50;

    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

//    /**
//     * @Route("/{id}/plan", name=".plan")
//     * @param ChildPchelo $childpchelo
//     * @param Request $request
//     * @param Plan\Set\Handler $handler
//     * @return Response
//     */
//    public function plan(ChildPchelo $childpchelo, Request $request, Plan\Set\Handler $handler): Response
//    {
////        $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);
//
//        $command = Plan\Set\Command::fromChildPchelo($this->getUser()->getId(), $childpchelo);
//
//        $form = $this->createForm(Plan\Set\Form::class, $command);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            try {
//                $handler->handle($command);
//                return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
//            } catch (\DomainException $e) {
//                $this->errors->handle($e);
//                $this->addFlash('error', $e->getMessage());
//            }
//        }
//
//        return $this->render('app/proekts/pasekas/childpchelos/plan.html.twig', [
//            'pchelomatka' => $childpchelo->getPlemPchelo(),
//            'childpchelo' => $childpchelo,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/{id}/plan/remove", name=".plan.remove", methods={"POST"})
//     * @param ChildPchelo $childpchelo
//     * @param Request $request
//     * @param Plan\Remove\Handler $handler
//     * @return Response
//     */
//    public function removePlan(ChildPchelo $childpchelo, Request $request, Plan\Remove\Handler $handler): Response
//    {
//        if (!$this->isCsrfTokenValid('remove-plan', $request->request->get('token'))) {
//            return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
//        }
//
////        $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);
//
//        $command = new Plan\Remove\Command($this->getUser()->getId(), $childpchelo->getId()->getValue());
//
//        try {
//            $handler->handle($command);
//        } catch (\DomainException $e) {
//            $this->errors->handle($e);
//            $this->addFlash('error', $e->getMessage());
//        }
//
//        return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
//    }

}

