<?php

declare(strict_types=1);

namespace App\Controller\Proekt\Pasekas\PcheloMatkas\ChildPchelo;

use App\Annotation\Guid;
use App\Controller\ErrorHandler;

use App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Create;

use App\Model\Adminka\Entity\PcheloMatkas\PcheloMatka\PcheloMatka;
use App\Model\Adminka\Entity\PcheloMatkas\PcheloMatka\Pchelosezon\Id;

use App\ReadModel\Adminka\PcheloMatkas\ChildPchelo\ChildPcheloFetcher;
use App\ReadModel\Adminka\PcheloMatkas\ChildPchelo\Filter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("app/proekts/pasekas/pchelomatkas/pchelomatka/{pchelomatka_id}/childpchelos", name="app.proekts.pasekas.pchelomatkas.pchelomatka.childpchelos")
 * @ParamConverter("pchelomatka", options={"id" = "pchelomatka_id"})
 */
class ChildPcheloController extends AbstractController
{
    private const PER_PAGE = 50;

    private $childpchelos;
    private $errors;

    public function __construct(ChildPcheloFetcher $childpchelos, ErrorHandler $errors)
    {
        $this->childpchelos = $childpchelos;
        $this->errors = $errors;
    }
// страничка конкретной  задачи
    /**
     * @Route("", name="")
     * @param PcheloMatka $pchelomatka
     * @param Request $request
     * @return Response
     */
    public function index(PcheloMatka $pchelomatka, Request $request): Response
    {
//        $this->denyAccessUnlessGranted(PcheloMatkaAccess::VIEW, $pchelomatka);

        $filter = Filter\Filter::forPcheloMatka($pchelomatka->getId()->getValue());

        $form = $this->createForm(Filter\Form::class, $filter);

        $form->handleRequest($request);


        $pagination = $this->childpchelos->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 't.date'),
            $request->query->get('direction', 'desc')
        );


        return $this->render('app/proekts/pasekas/childpchelos/index.html.twig', [
            'pchelomatka' => $pchelomatka,
//            'korotko' => $pchelomatka->getKorotkoName(),
            'pchelosezons' => $pchelomatka->getPchelosezons()  ,
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }
///////////////////////////////////////
//    /**
//     * @Route("/me", name=".me")
//     * @param PcheloMatka $pchelomatka
//     * @param Request $request
//     * @return Response
//     */
//    public function me(PcheloMatka $pchelomatka, Request $request): Response
//    {
////        $this->denyAccessUnlessGranted(PcheloMatkaAccess::VIEW, $pchelomatka);
//
//        $filter = Filter\Filter::forPcheloMatka($pchelomatka->getId()->getValue());
//
//        $form = $this->createForm(Filter\Form::class, $filter, [
//            'action' => $this->generateUrl('app.proekts.paekas.matkas.pchelomatkas.childpchelos', ['pchelomatka_id' => $pchelomatka->getId()]),
//        ]);
//        $form = $this->createForm(Filter\Form::class, $filter);
//        $form->handleRequest($request);
//
//        $pagination = $this->childpchelos->all(
//            $filter->forExecutor($this->getUser()->getId()),
//            $request->query->getInt('page', 1),
//            self::PER_PAGE,
//            $request->query->get('sort', 't.date'),
//            $request->query->get('direction', 'desc')
//        );
//
//        return $this->render('app/proekts/pasekas/childpchelos/index.html.twig', [
//            'pchelomatka' => $pchelomatka,
//            'pagination' => $pagination,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/own", name=".own")
//     * @param PcheloMatka $pchelomatka
//     * @param Request $request
//     * @return Response
//     */
//    public function own(PcheloMatka $pchelomatka, Request $request): Response
//    {
////        $this->denyAccessUnlessGranted(PcheloMatkaAccess::VIEW, $pchelomatka);
//
//        $filter = Filter\Filter::forPcheloMatka($pchelomatka->getId()->getValue());
//
//        $form = $this->createForm(Filter\Form::class, $filter, [
//        'action' => $this->generateUrl('app.proekts.paekas.matkas.pchelomatkas.childpchelos', ['pchelomatka_id' => $pchelomatka->getId()]),
//        ]);
//
//        $form->handleRequest($request);
//
//        $pagination = $this->childpchelos->all(
//            $filter->forAuthor($this->getUser()->getId()),
//            $request->query->getInt('page', 1),
//            self::PER_PAGE,
//            $request->query->get('sort'),
//            $request->query->get('direction')
//        );
//
//        return $this->render('app/proekts/pasekas/childpchelos/index.html.twig', [
//            'pchelomatka' => $pchelomatka,
//            'pagination' => $pagination,
//            'form' => $form->createView(),
//        ]);
//    }
/////////////////////////////
    /**
     * @Route("/{pchelosezon_id}/create", name=".create")
     * @param ChildPcheloFetcher $childFetcher
     * @param PcheloMatka $pchelomatka
     * @param string $pchelosezon_id
     * @param Request $request
     * @param Create\Handler $handler
     * @return Response
     */
    public function create(Request $request,
        PcheloMatka $pchelomatka,
        ChildPcheloFetcher $childFetcher,
        string $pchelosezon_id,
        Create\Handler $handler): Response
    {
//        $this->denyAccessUnlessGranted(PcheloMatkaAccess::VIEW, $pchelomatka);

        $pchelosezonPlem = $pchelomatka->getPchelosezon(new Id($pchelosezon_id))->getName();

        $maxKolChild = $childFetcher->getMaxKolChild($pchelomatka->getId()->getValue(),$pchelosezonPlem) + 1;
//        dd($maxKolChild);

        $command = new Create\Command(
            $pchelomatka->getId()->getValue(),
            $this->getUser()->getId(),
            $pchelomatka->getPchelosezon(new Id($pchelosezon_id))->getName(),
            $maxKolChild
        );

        if ($parent = $request->query->get('parent')) {
            $command->parent = $parent;
        }

        $form = $this->createForm(Create\Form::class, $command );
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
//dd($pchelomatka);
        return $this->render('app/proekts/pasekas/pchelomatkas/pchelomatka/childpchelos/create.html.twig', [
            'pchelomatka' => $pchelomatka,
            'pchelosezonPlem' => $pchelosezonPlem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show", name=".show", requirements={"id"=Guid::PATTERN})
     * @param PcheloMatka $pchelomatka
     * @param Request $request
     * @return Response
     */
    public function show(PcheloMatka $pchelomatka,
                         Request $request ): Response
    {
//        dd($pchelomatka);
        return $this->render('app/proekts/pasekas/pchelomatkas/pchelomatka/childpchelos/show.html.twig', [
            'pchelomatka' => $pchelomatka,
            'pchelovods' => $pchelomatka->getPchelovods() ,
            // 'comments' => $comments->allForPcheloMatka($pchelomatka->getId()->getValue()),
            // 'commentForm' => $commentForm->createView(),

        ]);
    }
}
