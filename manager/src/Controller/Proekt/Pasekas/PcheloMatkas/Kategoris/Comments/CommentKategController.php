<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\PcheloMatkas\Kategoris\Comments;

use App\Controller\ErrorHandler;

use App\Model\Adminka\Entity\PcheloMatkas\Kategoria\Kategoria;
use App\Model\Comment\Entity\Comment\Comment;
use App\Model\Comment\UseCase\Comment\Edit;
use App\Model\Comment\UseCase\Comment\Remove;
use App\Model\Comment\UseCase\Comment\CreateKategs;

use App\ReadModel\Adminka\PcheloMatkas\PcheloMatka\CommentPcheloFetcher;
use App\Security\Voter\Comment\CommentAccess;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("app/proekts/pasekas/pchelomatkas/kategoris/{kategoria_id}/comment", name="app.proekts.pasekas.pchelomatkas.kategoris.comment")
 * @ParamConverter("kategoria", options={"id" = "kategoria_id"})
 */
class CommentKategController extends AbstractController
{
    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("/kategcr", name=".kategcr")
     * @param Request $request,
     * @param Kategoria $kategoria
     * @param CommentPcheloFetcher $comments
     * @param CreateKategs\Handler $commentHandler
     * @return Response
     */
    public function kategcr(Request $request, Kategoria $kategoria,
                            CreateKategs\Handler $commentHandler,
                               CommentPcheloFetcher $comments): Response
    {

        $commentCommand = new CreateKategs\Command(
            $this->getUser()->getId(),
            Kategoria::class,
            $kategoria->getId()->getValue()
        );

        $commentForm = $this->createForm(CreateKategs\Form::class, $commentCommand);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            try {
                $commentHandler->handle($commentCommand);
                return $this->redirectToRoute('app.proekts.pasekas.pchelomatkas.kategoris.comment.kategcr', ['kategoria_id' => $kategoria->getId()]);
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }
        return $this->render('app/proekts/pasekas/pchelomatkas/kategoris/comment/kategcr.html.twig', [
            'kategoria' => $kategoria,
            'comments' => $comments->allForKategor($kategoria->getId()->getValue()) ,
            'commentForm' => $commentForm->createView(),
        ]);
    }


    /**
     * @Route("/{id}/editkat", name=".editkat")
     * @param Kategoria $kategoria
     * @param Comment $comment
     * @param Request $request
     * @param Edit\Handler $handler
     * @return Response
     */
    public function editkat(Request $request, Comment $comment, Kategoria $kategoria, Edit\Handler $handler): Response
    {

        $this->checkCommentIsForKateg($kategoria, $comment);
//        $this->denyAccessUnlessGranted(CommentAccess::MANAGE, $comment);

        $command = Edit\Command::fromComment($comment);

        $form = $this->createForm(Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('app.proekts.pasekas.pchelomatkas.kategoris.comment.kategcr', ['kategoria_id' => $kategoria->getId()]);
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }


        return $this->render('app/proekts/pasekas/pchelomatkas/kategoris/comment/editkat.html.twig', [
            'kategoria' => $kategoria,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name=".delete", methods={"POST"})
     * @param Kategoria $kategoria
     * @param Comment $comment
     * @param Request $request
     * @param Remove\Handler $handler
     * @return Response
     */
    public function delete(Kategoria $kategoria, Comment $comment, Request $request, Remove\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('delete-comment', $request->request->get('token'))) {
            return $this->redirectToRoute('app.proekts.pasekas.pchelomatkas.kategoris.comment.kategcr', ['kategoria_id' => $kategoria->getId()]);
        }

//        $this->denyAccessUnlessGranted(LiniaAccess::VIEW, $linia);
        $this->checkCommentIsForKateg($kategoria, $comment);
        $this->denyAccessUnlessGranted(CommentAccess::MANAGE, $comment);

        $command = new Remove\Command($comment->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app.proekts.pasekas.pchelomatkas.kategoris.comment.kategcr', ['kategoria_id' => $kategoria->getId()]);
    }

    private function checkCommentIsForKateg(Kategoria $kategoria, Comment $comment): void
    {
        if (!(
            $comment->getEntity()->getType() === Kategoria::class &&
           $comment->getEntity()->getId() === $kategoria->getId()->getValue()
        )) {
            throw $this->createNotFoundException();
        }
    }
}
