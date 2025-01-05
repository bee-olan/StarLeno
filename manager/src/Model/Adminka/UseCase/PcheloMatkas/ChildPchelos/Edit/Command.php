<?php

declare(strict_types=1);

namespace App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Edit;

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
//
//    /**
//     * @Assert\NotBlank()
//     */
//    public $name;

    public $content;

    public function __construct(string $actor, int $id)
    {
        $this->actor = $actor;
        $this->id = $id;
    }

    public static function fromChildPchelo(string $actor, ChildPchelo $childpchelo): self
    {
        $command = new self($actor, $childpchelo->getId()->getValue());

        $command->content = $childpchelo->getContent();
        return $command;
    }
}
