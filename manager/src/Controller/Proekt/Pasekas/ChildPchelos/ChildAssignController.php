<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\ChildPchelos;

use App\Model\Adminka\Entity\Uchasties\Uchastie\Uchastie;
use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Executor;
//use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelo\Plan;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
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
class ChildAssignController extends AbstractController
{
    private const PER_PAGE = 50;

    private $errors;

    public function __construct( ErrorHandler $errors)
    {
        $this->errors = $errors;
    }


    /**
     * @Route("/{id}/assign", name=".assign")
     * @param ChildPchelo $childpchelo
     * @param Request $request
     * @param Executor\Assign\Handler $handler
     * @return Response
     */
    public function assign(ChildPchelo $childpchelo, Request $request, Executor\Assign\Handler $handler): Response
    {
        $pchelomatka = $childpchelo->getPcheloMatka() ;

       // $this->denyAccessUnlessGranted(TaskAccess::MANAGE, $task);

        $command = new Executor\Assign\Command($this->getUser()->getId(), $childpchelo->getId()->getValue());
// dd  ($command);
        $form = $this->createForm(Executor\Assign\Form::class, $command, ['pchelomatka_id' => $pchelomatka->getId()->getValue()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/proekts/pasekas/childpchelos/assign.html.twig', [
            'pchelomatka' => $pchelomatka,
            'childpchelo' => $childpchelo,
            'form' => $form->createView(),
        ]);
    }

//    // revoke отменять
    /**
     * @Route("/{id}/revoke/{uchastie_id}", name=".revoke", methods={"POST"})
     * @ParamConverter("uchastie", options={"id" = "uchastie_id"})
     * @param ChildPchelo $childpchelo
     * @param Uchastie $uchastie
     * @param Request $request
     * @param Executor\Revoke\Handler $handler
     * @return Response
     */
    public function revoke(ChildPchelo $childpchelo, Uchastie $uchastie, Request $request, Executor\Revoke\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('revoke', $request->request->get('token'))) {
            return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
        }

        // $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);

        $command = new Executor\Revoke\Command(
            $this->getUser()->getId(),
            $childpchelo->getId()->getValue(),
            $uchastie->getId()->getValue()
        );

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
    }
}
