<?php

declare(strict_types=1);

namespace App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\CreateParent;

use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPcheloRepository;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Id;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Type;

use App\Model\Adminka\Entity\PcheloMatkas\PcheloMatka\PcheloMatkaRepository;
use App\Model\Adminka\Entity\PcheloMatkas\PcheloMatka\Id as PcheloMatkaId;
use App\Model\Flusher;

use App\Model\Adminka\Entity\Uchasties\Uchastie\Id as UchastieId;
use App\Model\Adminka\Entity\Uchasties\Uchastie\UchastieRepository;

use App\Model\Mesto\Entity\InfaMesto\MestoNomerRepository;
use App\Model\Mesto\Entity\InfaMesto\Id as MestoNomerId;
use App\Model\Adminka\Entity\Uchasties\Personas\PersonaRepository;
use App\Model\Adminka\Entity\Uchasties\Personas\Id as PersonaId;
use App\ReadModel\Adminka\PcheloMatkas\ChildPchelo\ChildPcheloFetcher;

class Handler
{
    private $uchasties;
    private $pchelomatkas;
    private $childpchelos;
    private $personas;
    private $mestonomers;
    private $childFetcher;
    private $flusher;

    public function __construct(
        UchastieRepository $uchasties,
        PcheloMatkaRepository $pchelomatkas,
        ChildPcheloRepository $childpchelos,
        ChildPcheloFetcher $childFetcher,
        PersonaRepository $personas,
        MestoNomerRepository $mestonomers,
        Flusher $flusher
    )
    {
        $this->uchasties = $uchasties;
        $this->pchelomatkas = $pchelomatkas;
        $this->childpchelos = $childpchelos;
        $this->childFetcher = $childFetcher;
        $this->personas = $personas;
        $this->mestonomers = $mestonomers;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $command->godaVixod = (int)$command->plan_date->format('Y');

        $parent = $command->parent ? $this->childpchelos->get(new Id((int)$command->parent)) : null;

//        if ( (int)$parent->getPchelosezonPlem() < $command->godaVixod ){
//            throw new \DomainException('Внимание! Исправьте год выхода матки. Дочь не может быть старше матери .');
//        }
        $tester = $this->childFetcher->getExecutorChild($parent->getId()->getValue());

        $persona = $this->personas->get(new PersonaId($tester))->getNomer();

        $uchastie = $this->uchasties->get(new UchastieId($tester));

        $pchelomatka = $this->pchelomatkas->get(new PcheloMatkaId($command->pchelomatka));


        $date = new \DateTimeImmutable();

        $command->plan = $date;

        $nameParents = explode(" : ", $parent->getName());
        $nameParent = $nameParents[0] . " : " . $nameParents[1] . "-" . $parent->getKolChild();

        $command->urowni = (int)mb_substr_count($nameParent, '-') - 1;

        $childpcheloId = $this->childpchelos->nextId();

        $command->pchelosezonChild = (string)$command->godaVixod; // ??????

        $command->name = $nameParent . " : пн-" . $persona . " : " . $command->personKolChild . "-" . $command->godaVixod;


        $childpchelo = new ChildPchelo(
            $childpcheloId,
            $pchelomatka,
            $uchastie ,
            $command->plan_date,
            new Type($command->type),
            $command->priority,
            $command->name,
            $command->content,
            $command->kolChild = $command->personKolChild,
            $command->godaVixod,
            $pchelosezonPlem = $parent->getPchelosezonPlem(),
            $command->pchelosezonChild,
            $command->urowni
        );


        if ($parent) {
            $childpchelo->setChildOf($uchastie, $date, $parent);
        }

        if ($command->plan) {
            $childpchelo->plan($uchastie, $date, $command->plan);
        }

        $this->childpchelos->add($childpchelo);
//        }
        $this->flusher->flush();
    }
}
