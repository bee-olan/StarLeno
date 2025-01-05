<?php

declare(strict_types=1);

namespace App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Executor\Revoke;

use App\Model\Flusher;
use App\Model\Adminka\Entity\Uchasties\Uchastie\Id as UchastieId;
use App\Model\Adminka\Entity\Uchasties\Uchastie\UchastieRepository;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Id;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPcheloRepository;

class Handler
{
    private $childpchelos;
    private $flusher;
    private $uchasties;

    public function __construct(
        ChildPcheloRepository $childpchelos,
        UchastieRepository $uchasties,
        Flusher $flusher
    )
    {
        $this->childpchelos = $childpchelos;
        $this->flusher = $flusher;
        $this->uchasties = $uchasties;
    }

    public function handle(Command $command): void
    {
        $actor = $this->uchasties->get(new UchastieId($command->actor));
        $childpchelo = $this->childpchelos->get(new Id($command->id));
        $uchastie = $this->uchasties->get(new UchastieId($command->uchastie));

        $childpchelo->revokeExecutor($actor, new \DateTimeImmutable(),$uchastie->getId());

//        $childpchelo->changeType();   zakaz($actor,new \DateTimeImmutable());
        $childpchelo->isNew();
        $this->flusher->flush();
    }
}

