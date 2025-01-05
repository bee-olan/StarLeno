<?php

declare(strict_types=1);

namespace App\ReadModel\Adminka\PcheloMatkas\ChildPchelo\Filter;

class Filter
{
    public $uchastie;
    public $author;
    public $pchelomatka;
    public $text;
    public $goda_vixod;
    public $type;
    public $status;
    public $priority;
    public $executor;
    public $name;
    public $roots; /// верхний уровень без дочерних
    public $urowni;

    private function __construct(?string $pchelomatka)
    {
        $this->pchelomatka = $pchelomatka;
    }

    public static function forPcheloMatka(string $pchelomatka): self
    {
        return new self($pchelomatka);
    }

    public static function allChildPchelo(): self
    {
        return new self(null);
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

    public function forAuthor(string $author): self
    {
        $clone = clone $this;
        $clone->author = $author;
        return $clone;
    }

    public function forExecutor(string $executor): self
    {
        $clone = clone $this;
        $clone->executor = $executor;
        return $clone;
    }
}
