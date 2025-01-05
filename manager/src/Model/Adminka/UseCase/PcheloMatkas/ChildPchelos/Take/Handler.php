<?php

declare(strict_types=1);

namespace App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Take;

use App\Model\Flusher;
use App\Model\Adminka\Entity\Uchasties\Uchastie\Id as UchastieId;
use App\Model\Adminka\Entity\Uchasties\Uchastie\UchastieRepository;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Id;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPcheloRepository;

class Handler
{
    private $childpchelos;
    private $flusher;
    private $uchastes;

    public function __construct(
        ChildPcheloRepository $childpchelos,
        UchastieRepository $uchastes,
        Flusher $flusher
    )
    {
        $this->childpchelos = $childpchelos;
        $this->flusher = $flusher;
        $this->uchastes = $uchastes;
    }

    public function handle(Command $command): void
    {
        $childpchelo = $this->childpchelos->get(new Id($command->id));
        $actor = $this->uchastes->get(new UchastieId($command->actor));

        if (!$childpchelo->hasExecutor($actor->getId())) {
            $childpchelo->assignExecutor($actor, new \DateTimeImmutable(), $actor);
        }

        $childpchelo->zakaz($actor,new \DateTimeImmutable());

        $this->flusher->flush();
    }
}


