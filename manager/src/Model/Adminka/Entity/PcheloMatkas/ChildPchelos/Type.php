<?php

declare(strict_types=1);

namespace App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos;

use Webmozart\Assert\Assert;

class Type
{
    public const TF90 = 'тф-90';
    public const TF50 = 'тф-50';
    public const TFBK = 'тф-бк';
    

    private $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::TF90,
            self::TF50,
            self::TFBK,
           
        ]);

        $this->name = $name;
    }

    public function isEqual(self $other): bool
    {
        return $this->getName() === $other->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }
}
