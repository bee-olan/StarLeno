<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\ChildPchelos;

use App\Model\Comment\UseCase\Comment;

use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Executor;
use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Plan;
use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Priority;
use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Status;
use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Type;

use App\Controller\ErrorHandler;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
use App\ReadModel\Adminka\PcheloMatkas\Actions\ActionFetcher;
use App\ReadModel\Adminka\PcheloMatkas\Actions\Feed\Feed;
use App\ReadModel\Adminka\PcheloMatkas\ChildPchelo\ChildPcheloFetcher;

use App\ReadModel\Adminka\PcheloMatkas\ChildPchelo\CommentFetcher;
use App\ReadModel\Adminka\PcheloMatkas\PchelosezonFetcher;
//use App\ReadModel\Adminka\PcheloMatkas\PeriodFetcher;

use App\ReadModel\Adminka\Uchasties\Uchastie\UchastieFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("app/proekts/pasekas/childpchelos", name="app.proekts.pasekas.childpchelos")
 * @ParamConverter("pchelomatka", options={"id" = "pchelomatka_id"})
 */
class ChildShowController extends AbstractController
{
    private const PER_PAGE = 50;

    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }


    /**
     * @Route("/{id}", name=".show"))
     * @param ChildPchelo $childpchelo
     * @param Request $request
     * @param UchastieFetcher $uchasties
     * @param ChildPcheloFetcher $childpchelos
     * @param CommentFetcher $comments
     * @param ActionFetcher $actions
     * @param PchelosezonFetcher $periodFetchers
     * @param Status\Handler $statusHandler
     * @param Type\Handler $typeHandler
     * @param Priority\Handler $priorityHandler
     * @param Comment\CreatePchelo\Handler $commentHandler
     * @return Response
     */
    public function show(
        ChildPchelo $childpchelo,
        Request $request,
        UchastieFetcher $uchasties,
        ChildPcheloFetcher $childpchelos,
        CommentFetcher $comments,
        ActionFetcher $actions,
        PchelosezonFetcher $periodFetchers,
        Status\Handler $statusHandler,
        Type\Handler $typeHandler,
        Priority\Handler $priorityHandler,
        Comment\CreatePchelo\Handler $commentHandler
    ): Response {
        //  $this->denyAccessUnlessGranted(TaskAccess::VIEW, $task);

        if (!$uchastie = $uchasties->find($this->getUser()->getId())) {
            throw $this->createAccessDeniedException();
        }
        $periodFetcher = $periodFetchers->listOfPcheloMatka($childpchelo->getPcheloMatka()->getId()->getValue());


        $statusCommand = Status\Command::fromChildPchelo($this->getUser()->getId(), $childpchelo);
        $statusForm = $this->createForm(Status\Form::class, $statusCommand);
        $statusForm->handleRequest($request);
        if ($statusForm->isSubmitted() && $statusForm->isValid()) {
            try {
                $statusHandler->handle($statusCommand);
                return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }


        $typeCommand = Type\Command::fromChildPchelo($this->getUser()->getId(), $childpchelo);
        $typeForm = $this->createForm(Type\Form::class, $typeCommand);
        $typeForm->handleRequest($request);
        if ($typeForm->isSubmitted() && $typeForm->isValid()) {
            try {
                $typeHandler->handle($typeCommand);
                return $this->redirectToRoute(
                    'app.proekts.pasekas.childpchelos.show',['id' => $childpchelo->getId()]
                );
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        $priorityCommand = Priority\Command::fromChildPchelo($this->getUser()->getId(), $childpchelo);
        $priorityForm = $this->createForm(Priority\Form::class, $priorityCommand);
        $priorityForm->handleRequest($request);
        if ($priorityForm->isSubmitted() && $priorityForm->isValid()) {
            try {
                $priorityHandler->handle($priorityCommand);
                return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        $commentCommand = new Comment\CreatePchelo\Command(
            $this->getUser()->getId(),
            ChildPchelo::class,
            (int)$childpchelo->getId()->getValue()
        );

        $commentForm = $this->createForm(Comment\CreatePchelo\Form::class, $commentCommand);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            try {
                $commentHandler->handle($commentCommand);
                return $this->redirectToRoute('app.proekts.pasekas.childpchelos.show', ['id' => $childpchelo->getId()]);
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }
        $feed = new Feed(
            $actions->allForChildPchelo($childpchelo->getId()->getValue()),
            $comments->allForChildPchelo($childpchelo->getId()->getValue())
        );
//dd($childpchelos->childrenOf($childpchelo->getId()->getValue()));
        return $this->render(
            'app/proekts/pasekas/childpchelos/show.html.twig',
            [
                'pchelomatka' => $childpchelo->getPcheloMatka(),
//                'korotkoName' => $childpchelo->getPcheloMatka()->getKorotkoName(),
                'childpchelo' => $childpchelo,
                'uchastie' => $uchastie,
                'children' => $childpchelos->childrenOf($childpchelo->getId()->getValue()),
                'comments' => $comments->allForChildPchelo($childpchelo->getId()->getValue()),
                'actions' => $actions->allForChildPchelo($childpchelo->getId()->getValue()),
                'statusForm' => $statusForm->createView(),
                'typeForm' => $typeForm->createView(),
                'priorityForm' => $priorityForm->createView(),
                'commentForm' => $commentForm->createView(),
                'feed'=>$feed,
//                'periodId' => $childpchelo->idPerio($periodFetcher)
            ]
        );
    }

}
