<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\ChildPchelos\ChildPchelo;

use App\Controller\ErrorHandler;
use App\Model\Comment\Entity\Comment\Comment;
use App\Model\Comment\UseCase\Comment\Edit;
use App\Model\Comment\UseCase\Comment\Remove;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
use App\Security\Voter\Comment\CommentAccess;
//use App\Security\Voter\Adminka\Matkas\ChildMatkaAccess;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("app/proekts/pasekas/childpchelos/{childpchelo_id}/comment", name="app.proekts.pasekas.childpchelos.comment")
 * @ParamConverter("childpchelo", options={"id" = "childpchelo_id"})
 */
class CommentController extends AbstractController
{
    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("/{id}/edit", name=".edit")
     * @param ChildPchelo $childpchelo
     * @param Comment $comment
     * @param Request $request
     * @param Edit\Handler $handler
     * @return Response
     */
    public function edit(ChildPchelo $childpchelo, Comment $comment, Request $request, Edit\Handler $handler): Response
    {
//        $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);
        $this->checkCommentIsForChildPchelo($childpchelo, $comment);
//        $this->denyAccessUnlessGranted(CommentAccess::MANAGE, $comment);

        $command = Edit\Command::fromComment($comment);

        $form = $this->createForm(Edit\Form::class, $command);
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

        return $this->render('app/proekts/pasekas/childpchelos/comment/edit.html.twig', [
            'pchelomatka' => $childpchelo->getPcheloMatka(),
            'childpchelo' => $childpchelo,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}/delete", name=".delete", methods={"POST"})
//     * @param ChildPchelo $childpchelo
//     * @param Comment $comment
//     * @param Request $request
//     * @param Remove\Handler $handler
//     * @return Response
//     */
//    public function delete(ChildPchelo $childpchelo, Comment $comment, Request $request, Remove\Handler $handler): Response
//    {
//        if (!$this->isCsrfTokenValid('delete-comment', $request->request->get('token'))) {
//            return $this->redirectToRoute('app.adminka.pchelomatkas.childpchelos.show', ['id' => $childpchelo->getId()]);
//        }
//
////        $this->denyAccessUnlessGranted(ChildPcheloAccess::VIEW, $childpchelo);
//        $this->checkCommentIsForChildPchelo($childpchelo, $comment);
//        $this->denyAccessUnlessGranted(CommentAccess::MANAGE, $comment);
//
//        $command = new Remove\Command($comment->getId()->getValue());
//
//        try {
//            $handler->handle($command);
//        } catch (\DomainException $e) {
//            $this->errors->handle($e);
//            $this->addFlash('error', $e->getMessage());
//        }
//
//        return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', [
//            'id' => $childpchelo->getId()
//        ]);
//    }

    private function checkCommentIsForChildPchelo(ChildPchelo $childpchelo, Comment $comment): void
    {
        if (!(
            $comment->getEntity()->getType() === ChildPchelo::class &&
            (int)$comment->getEntity()->getId() === $childpchelo->getId()->getValue()
        )) {
            throw $this->createNotFoundException();
        }
    }
}
