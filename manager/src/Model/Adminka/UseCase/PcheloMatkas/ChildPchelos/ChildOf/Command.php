<?php

declare(strict_types=1);

namespace App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\ChildOf;

use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $actor;

    /**
     * @Assert\NotBlank()
     */
    public $id;

    public $parent;

    public function __construct(string $actor, int $id)
    {
        $this->actor = $actor;
        $this->id = $id;
    }

    public static function fromChildPchelo(string $actor, ChildPchelo $childpchelo): self
    {
        $command = new self($actor, $childpchelo->getId()->getValue());
        $command->parent = $childpchelo->getParent() ? $childpchelo->getParent()->getId()->getValue() : null;
        return $command;
    }
}