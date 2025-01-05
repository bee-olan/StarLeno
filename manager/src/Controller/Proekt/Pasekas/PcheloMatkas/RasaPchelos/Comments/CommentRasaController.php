<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\PcheloMatkas\RasaPchelos\Comments;

use App\Controller\ErrorHandler;

use App\Model\Comment\Entity\Comment\Comment;
use App\Model\Comment\UseCase\Comment\Edit;
use App\Model\Comment\UseCase\Comment\Remove;
use App\Model\Comment\UseCase\Comment\CreatePchelo;

use App\Model\Drevos\Entity\Rass\Ras;
use App\ReadModel\Adminka\PcheloMatkas\PcheloMatka\CommentPcheloFetcher;
use App\Security\Voter\Comment\CommentAccess;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("app/proekts/pasekas/pchelomatkas/rasa-pchelos/{rasa_id}/comment", name="app.proekts.pasekas.pchelomatkas.rasa-pchelos.comment")
 * @ParamConverter("rasa", options={"id" = "rasa_id"})
 */
class CommentRasaController extends AbstractController
{
    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("/rasacr", name=".rasacr")
     * @param Request $request,
     * @param Ras $rasa
     * @param CommentPcheloFetcher $comments
     * @param CreatePchelo\Handler $commentHandler
     * @return Response
     */
    public function rasacr(Request $request, Ras $rasa,
                               CreatePchelo\Handler $commentHandler,
                               CommentPcheloFetcher $comments): Response
    {
//        $rasas = $fetcher->all();
//dd($rasa);
        $commentCommand = new CreatePchelo\Command(
            $this->getUser()->getId(),
            Ras::class,
            $rasa->getId()->getValue()
        );

        $commentForm = $this->createForm(CreatePchelo\Form::class, $commentCommand);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            try {
                $commentHandler->handle($commentCommand);
                return $this->redirectToRoute('app.proekts.pasekas.pchelomatkas.rasa-pchelos.comment.rasacr', ['rasa_id' => $rasa->getId()]);
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }
        return $this->render('app/proekts/pasekas/pchelomatkas/rasa-pchelos/comment/rasacr.html.twig', [
            'rasa' => $rasa,
            'comments' => $comments->allForRasPchelo($rasa->getId()->getValue()),
            'commentForm' => $commentForm->createView(),
        ]);



    }
    /**
     * @Route("/{id}/edit", name=".edit")
     * @param Ras $rasa
     * @param Comment $comment
     * @param Request $request
     * @param Edit\Handler $handler
     * @return Response
     */
    public function edit(Request $request, Comment $comment, Ras $rasa, Edit\Handler $handler): Response
    {

        $this->checkCommentIsForRasa($rasa, $comment);
//        $this->denyAccessUnlessGranted(CommentAccess::MANAGE, $comment);

        $command = Edit\Command::fromComment($comment);

        $form = $this->createForm(Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('app.proekts.pasekas.pchelomatkas.rasa-pchelos.comment.rasacr', ['rasa_id' => $rasa->getId()]);
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/proekts/pasekas/pchelomatkas/rasa-pchelos/comment/edit.html.twig', [
            'rasa' => $rasa,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name=".delete", methods={"POST"})
     * @param Ras $rasa
     * @param Comment $comment
     * @param Request $request
     * @param Remove\Handler $handler
     * @return Response
     */
    public function delete(Ras $rasa, Comment $comment, Request $request, Remove\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('delete-comment', $request->request->get('token'))) {
            return $this->redirectToRoute('app.proekts.pasekas.pchelomatkas.rasa-pchelos.comment.rasacr', ['rasa_id' => $rasa->getId()]);
        }

//        $this->denyAccessUnlessGranted(LiniaAccess::VIEW, $linia);
        $this->checkCommentIsForRasa($rasa, $comment);
        $this->denyAccessUnlessGranted(CommentAccess::MANAGE, $comment);

        $command = new Remove\Command($comment->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app.proekts.pasekas.pchelomatkas.rasa-pchelos.comment.rasacr', ['rasa_id' => $rasa->getId()]);
    }

    private function checkCommentIsForRasa(Ras $rasa, Comment $comment): void
    {
        if (!(
            $comment->getEntity()->getType() === Ras::class &&
            $comment->getEntity()->getId() === $rasa->getId()->getValue()
        )) {
            throw $this->createNotFoundException();
        }
    }
}
