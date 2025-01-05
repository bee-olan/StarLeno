<?php

declare(strict_types=1);

namespace App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Start;

use App\Model\Flusher;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Id;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPcheloRepository;
use App\Model\Adminka\Entity\Uchasties\Uchastie\Id as UchastieId;
use App\Model\Adminka\Entity\Uchasties\Uchastie\UchastieRepository;


class Handler
{
    private $uchasties;
    private $childpchelos;
    private $flusher;

    public function __construct(UchastieRepository $uchasties,
                                ChildPcheloRepository $childpchelos, Flusher $flusher)
    {
        $this->uchasties = $uchasties;
        $this->childpchelos = $childpchelos;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $actor = $this->uchasties->get(new UchastieId($command->actor));
        $childpchelo = $this->childpchelos->get(new Id($command->id));

        $childpchelo->start($actor,new \DateTimeImmutable());

        $this->flusher->flush();
    }
}

