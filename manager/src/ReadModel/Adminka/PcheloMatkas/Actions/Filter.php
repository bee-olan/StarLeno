<?php

declare(strict_types=1);

namespace App\ReadModel\Adminka\PcheloMatkas\Actions;


class Filter
{
    public $uchastie;
    public $pchelomatka;

    private function __construct(?string $pchelomatka)
    {
        $this->pchelomatka = $pchelomatka;
    }

    public static function forPcheloMatka(string $pchelomatka): self
    {
        return new self($pchelomatka);
    }

    public static function all(): self
    {
        return new self(null);
    }

    public function forUchastie(string $uchastie): self
    {
        $clone = clone $this;
        $clone->uchastie = $uchastie;
        return $clone;
    }
}

