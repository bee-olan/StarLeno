<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\ChildPchelos;

use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\ChildOf;
use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Executor;

use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Plan;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
//use App\ReadModel\Proekt\Pasekas\ChildPchelos\Side\ChildSideFetcher;

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
class ChildOfController extends AbstractController
{
    private const PER_PAGE = 50;

    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("/{id}/child", name=".child")
     * @param ChildPchelo $childpchelo
     * @param Request $request
     * @param ChildOf\Handler $handler
     * @return Response
     */
    public function childOf(ChildPchelo $childpchelo, Request $request, ChildOf\Handler $handler): Response
    {
//        $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);

        $command = ChildOf\Command::fromChildPchelo($this->getUser()->getId(), $childpchelo);

        $form = $this->createForm(ChildOf\Form::class, $command);
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

        return $this->render('app/proekts/pasekas/childpchelos/child.html.twig', [
            'pchelomatka' => $childpchelo->getPcheloMatka(),
            'childpchelo' => $childpchelo,
            'form' => $form->createView(),
        ]);
    }
}



 
