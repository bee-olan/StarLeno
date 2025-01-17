<?php

declare(strict_types=1);

namespace App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos;

use Webmozart\Assert\Assert;

class Status
{
    public const NEW = 'Новая';
    public const ZAKAZ = 'Заказана';
    public const WORKING = 'В работе';
    public const HELP = 'Вопрос';
    public const REJECTED = 'ПлемМатка';
    public const DONE = 'Завершено';

    private $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::NEW,
            self::ZAKAZ,
            self::WORKING,
            self::HELP,
            self::REJECTED,
            self::DONE,
        ]);

        $this->name = $name;
    }

    public static function new(): self
    {
        return new self(self::NEW);
    }

    public static function perewodPchelo(): self
    {
        return new self(self::REJECTED);
    }

    public static function zakaz(): self
    {
        return new self(self::ZAKAZ);
    }

    public static function working(): self
    {
        return new self(self::WORKING);
    }

    public function isEqual(self $other): bool
    {
        return $this->getName() === $other->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isDone(): bool
    {
        return $this->name === self::DONE;
    }

    public function isNew(): bool
    {
        return $this->name === self::NEW;
    }

    public function isPerewodPchelo(): bool
    {
        return $this->name === self::REJECTED;
    }

    public function isZakaz(): bool
    {
        return $this->name === self::ZAKAZ;
    }

    public function isWorking(): bool
    {
        return $this->name === self::WORKING;
    }
}