<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\ChildPchelos;

use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Zakaz;
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
class ChildZakazStartController extends AbstractController
{
    private const PER_PAGE = 50;

    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("/{id}/zakaz", name=".zakaz", methods={"POST"})
     * @param ChildPchelo $childpchelo
     * @param Request $request
     * @param Zakaz\Handler $handler
     * @return Response
     */
    public function zakaz(ChildPchelo $childpchelo, Request $request, Zakaz\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('zakaz', $request->request->get('token'))) {
            return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
        }

        // $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);

        $command = new Zakaz\Command($this->getUser()->getId(), $childpchelo->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
    }

}

