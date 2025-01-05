<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\ChildPchelos;

use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Files;

use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Executor;

use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Plan;

use App\Controller\ErrorHandler;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
//use App\Security\Voter\Adminka\PcheloMatkas\ChildPcheloAccess;
use App\Service\Uploader\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("app/proekts/pasekas/childpchelos", name="app.proekts.pasekas.childpchelos")
 * @ParamConverter("pchelomatka", options={"id" = "pchelomatka_id"})
 */
class ChildFilesController extends AbstractController
{
    private const PER_PAGE = 50;

    private $errors;

    public function __construct( ErrorHandler $errors)
    {
        $this->errors = $errors;
    }


    /**
     * @Route("/{id}/files", name=".files")
     * @param ChildPchelo $childpchelo
     * @param Request $request
     * @param Files\Add\Handler $handler
     * @param FileUploader $uploader
     * @return Response
     */
    public function files( Request $request, Files\Add\Handler $handler,
                           ChildPchelo $childpchelo,
                           FileUploader $uploader): Response
    {
//        $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);

        $command = new Files\Add\Command($this->getUser()->getId(), $childpchelo->getId()->getValue() );

        $form = $this->createForm(Files\Add\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = [];

            foreach ($form->get('files')->getData() as $file) {
                $uploaded = $uploader->upload($file);
                $files[] = new Files\Add\File(
                    $uploaded->getPath(),
                    $uploaded->getName(),
                    $uploaded->getSize()
                );

            }
            $command->files = $files;

            try {
                $handler->handle($command);
                return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/proekts/pasekas/childpchelos/files.html.twig', [
            'pchelomatka' => $childpchelo->getPcheloMatka(),
            'childpchelo' => $childpchelo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/files/{file_id}/delete", name=".files.delete", methods={"POST"})
     * @ParamConverter("uchastie", options={"id" = "uchastie_id"})
     * @param ChildPchelo $childpchelo
     * @param string $file_id
     * @param Request $request
     * @param Files\Remove\Handler $handler
     * @return Response
     */
    public function fileDelete(ChildPchelo $childpchelo, string $file_id, Request $request, Files\Remove\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('revoke', $request->request->get('token'))) {
            return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
        }

//        $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);

        $command = new Files\Remove\Command($this->getUser()->getId(), $childpchelo->getId()->getValue(), $file_id);

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
    }



}


