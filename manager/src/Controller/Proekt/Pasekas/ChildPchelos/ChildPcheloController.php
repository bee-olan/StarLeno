<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\ChildPchelos;

use App\Controller\ErrorHandler;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPcheloRepository;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Id;
use App\Model\Adminka\Entity\PcheloMatkas\PcheloMatka\PcheloMatka;
//use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Edit;
use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\CreateParent;
//use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Remove;
//use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
//use App\Model\Adminka\Entity\PcheloMatkas\PcheloMatka\PcheloMatka;
//use App\ReadModel\Adminka\PcheloMatkas\ChildPchelos\ChildPcheloFetcher;
//use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
use App\ReadModel\Adminka\PcheloMatkas\ChildPchelo\Filter;

use App\ReadModel\Adminka\PcheloMatkas\ChildPchelo\ChildPcheloFetcher;
//use App\ReadModel\Adminka\PcheloMatkas\PcheloMatka\PcheloMatkaFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("app/proekts/pasekas/childpchelos", name="app.proekts.pasekas.childpchelos")
 * @ParamConverter("pchelomatka", options={"id" = "pchelomatka_id"})
 */
class ChildPcheloController extends AbstractController
{
    private const PER_PAGE = 10;

    private $errors;

    public function __construct( ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("", name="")
     * @param Request $request
     * @param ChildPcheloFetcher $childFets
     * @return Response
     */
    public function index(Request $request, ChildPcheloFetcher $childFets): Response
    {

//        dd($pchelomatka );

//         if ($this->isGranted('ROLE_ADMINKA_MANAGE_PLEMMATKAS')) {
             $filter = Filter\Filter::all();
//         } else {
//            $filter = Filter\Filter::all()->forUchastie($this->getUser()->getId());
//        }
//        $childpchelos = $childFets->allwse();
        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $childFets->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort'),
            $request->query->get('direction')
        );


        return $this->render('app/proekts/pasekas/childpchelos/index.html.twig', [
            'proba' => 'проба',
            'pchelomatka' => null,
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{pchelomatka_id}/create", name=".create")
     * @param PcheloMatka $pchelomatka
     * @param ChildPcheloRepository $childpchelos
     * @param ChildPcheloFetcher $childFetcher
     * @param Request $request
     * @param CreateParent\Handler $handler
     * @return Response
     */
    public function create( Request $request,
        PcheloMatka $pchelomatka,
        ChildPcheloFetcher $childFetcher,
        ChildPcheloRepository $childpchelos,
        CreateParent\Handler $handler): Response
    {
//        $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);
        $command = new CreateParent\Command(
            $pchelomatka->getId()->getValue(),
            $this->getUser()->getId()

//            $this->pchelosezonPlem,
//            $this->maxKolChild
        );

        if ($parent = $request->query->get('parent')) {
            $command->parent = $parent;
            $childpchelo = $childpchelos->get(new Id((int)$parent));
            $pchelosezonChild =   (int)$childpchelo->getPchelosezonChild() ;
        }

//dd($childFetcher->getExecutorChild((int)$parent) );
        $form = $this->createForm(CreateParent\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('app.proekts.pasekas.childpchelos');
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/proekts/pasekas/childpchelos/create.html.twig', [
            'pchelomatka' => $pchelomatka,
            'childpchelo' => $childpchelo,
            'pchelosezonChild' => $pchelosezonChild,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}/edit", name=".edit")
//     * @param ChildPchelo $childpchelo
//     * @param Request $request
//     * @param Edit\Handler $handler
//     * @return Response
//     */
//    public function edit(ChildPchelo $childpchelo, Request $request, Edit\Handler $handler): Response
//    {
////        $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);
//
//        $command = Edit\Command::fromChildPchelo($this->getUser()->getId(), $childpchelo);
//
//        $form = $this->createForm(Edit\Form::class, $command);
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
//        return $this->render('app/proekts/pasekas/childpchelos/edit.html.twig', [
//            'pchelomatka' => $childpchelo->getPcheloMatka(),
//            'childpchelo' => $childpchelo,
//            'form' => $form->createView(),
//        ]);
//    }

//   /**
//     * @Route("/{id}/move", name=".move")
//     * @param ChildPchelo $childpchelo
//     * @param Request $request
//     * @param Move\Handler $handler
//     * @return Response
//     */
//    public function move(ChildPchelo $childpchelo, Request $request, Remove\Handler $handler): Response
//    {
//        // $this->denyAccessUnlessGranted(ChildPcheloAccess::MANAGE, $childpchelo);
//
//        $command = Move\Command::fromChildPchelo($this->getUser()->getId(), $childpchelo);
//
//        $form = $this->createForm(Move\Form::class, $command);
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
//        return $this->render('app.proekts/pasekas/childpchelos/move.html.twig', [
//            'pchelomatka' => $childpchelo->getPcheloMatka(),
//            'childpchelo' => $childpchelo,
//            'form' => $form->createView(),
//        ]);
//    }


}


