<?php

declare(strict_types=1);

namespace App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Executor\Assign;

use App\Model\Flusher;
use App\Model\Adminka\Entity\Uchasties\Uchastie\Id as UchastieId;
use App\Model\Adminka\Entity\Uchasties\Uchastie\UchastieRepository;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Id;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPcheloRepository;

class Handler
{
    private $chilpchelos;
    private $flusher;
    private $uchasties;

    public function __construct(
        ChildPcheloRepository $chilpchelos,
        UchastieRepository $uchasties,
        Flusher $flusher
    )
    {
        $this->chilpchelos = $chilpchelos;
        $this->flusher = $flusher;
        $this->uchasties = $uchasties;
    }

    public function handle(Command $command): void
    {
        $actor = $this->uchasties->get(new UchastieId($command->actor));
        $chilpchelo = $this->chilpchelos->get(new Id($command->id));

        foreach ($command->uchasties as $id) {
            $uchastie = $this->uchasties->get(new UchastieId($id));
            if (!$chilpchelo->hasExecutor($uchastie->getId())) {
                $chilpchelo->assignExecutor($actor, new \DateTimeImmutable(),$uchastie);
            }
        }
        $chilpchelo->zakaz($actor,new \DateTimeImmutable());

        $this->flusher->flush();
    }
}


