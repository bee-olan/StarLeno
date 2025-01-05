<?php

declare(strict_types=1);

namespace App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Create;

use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPcheloRepository;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Id;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Type;

use App\Model\Adminka\Entity\PcheloMatkas\PcheloMatka\PcheloMatkaRepository;
use App\Model\Adminka\Entity\PcheloMatkas\PcheloMatka\Id as PcheloMatkaId;
use App\Model\Flusher;


use App\Model\Adminka\Entity\Uchasties\Uchastie\Id as UchastieId;
use App\Model\Adminka\Entity\Uchasties\Uchastie\UchastieRepository;

class Handler
{
    private $uchasties;
    private $pchelomatkas;
    private $childpchelos;
//    private $personas;
//    private $mestonomers;
    private $flusher;

    public function __construct(
        UchastieRepository $uchasties,
        PcheloMatkaRepository $pchelomatkas,
        ChildPcheloRepository $childpchelos,
//        PersonaRepository $personas,
//        MestoNomerRepository $mestonomers,
        Flusher $flusher
    )
    {
        $this->uchasties = $uchasties;
        $this->pchelomatkas = $pchelomatkas;
        $this->childpchelos = $childpchelos;
//        $this->personas = $personas;
//        $this->mestonomers = $mestonomers;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
//
//        $parent = $command->parent ? $this->childpchelos->get(new Id((int)$command->parent)) : null;

        $uchastie = $this->uchasties->get(new UchastieId($command->uchastie));

        $pchelomatka = $this->pchelomatkas->get(new PcheloMatkaId($command->pchelomatka));


        $mestonomer = $pchelomatka->getMesto()->getNomer();

        $persona = $pchelomatka->getPersona()->getNomer();

        $command->godaVixod = (int)$command->plan_date->format('Y');

        if ((int)$command->pchelosezonPlem < (int)$command->godaVixod) {
            throw new \DomainException('Внимание! Исправьте год выхода матки. Дочь не может быть старше матери .');
        }
        $mesPlem = (int)$pchelomatka->getDateVixod()->format('m');
        $mesChild = (int)$command->plan_date->format('m');


        if ($mesChild - $mesPlem <= 1) {
            throw new \DomainException('Внимание ! Исправьте месяц выхода матки .');
        }

        $date = new \DateTimeImmutable();
        $command->plan = $date;
//
//        if ($parent) {
//
//            $nameParents = explode(" : ", $parent->getName());
//            $nameParent = $nameParents[0] . " : " .$nameParents[1] . "-" . $parent->getId();
//
//            $urowni = mb_substr_count($nameParent, '-');
//
//            $nachal = $konec = 1;
//        } else {

        $plem = explode("-", $pchelomatka->getTitle());
//dd($plem );
        $plemName = explode(" : ", $pchelomatka->getName());

        $nameParent = $plemName[0] . " : " .$plem[0] . "-" . $pchelomatka->getKategoria()->getName() ;

        if (($command->kolChild - $command->personKolChild) < -0.1) {
            $nachal = (int)$command->personKolChild;
        } else {
            $nachal = (int)$command->kolChild;
        }

        $konec = (int)($nachal + $command->kol);
//        }

        for ($i = $nachal; $i < $konec; $i++) {
            $childpcheloId = $this->childpchelos->nextId();

            $pchelosezonPlem = $command->pchelosezonPlem;

            $command->name = $nameParent . " : пн-" . $persona . " : " . $i . "-" . $command->godaVixod;

            $command->pchelosezonChild = (string)$command->godaVixod;


            $childpchelo = new ChildPchelo(
                $childpcheloId,
                $pchelomatka,
                $uchastie,
                $command->plan_date,
                new Type($command->type),
                $command->priority,
                $command->name,
                $command->content,
                $command->kolChild = $i,
                $command->godaVixod,
                $pchelosezonPlem,
                $command->pchelosezonChild,
                $urowni = 1
            );


//            if ($parent) {
//                $childpchelo->setChildOf($uchastie, $date, $parent);
//            }

            if ($command->plan) {
                $childpchelo->plan($uchastie, $date, $command->plan);
            }

            $this->childpchelos->add($childpchelo);
        }
        $this->flusher->flush();
    }
}
