<?php

declare(strict_types=1);

namespace App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Create;

//use App\Model\Adminka\Entity\Matkas\ChildMatka\Type;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $pchelomatka;
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $uchastie;


    /**
     * @var string
     */
    public $content;

    /**
     * @var int
     */
    public $parent;

    /**
     * @var \DateTimeImmutable
     * @Assert\Date()
     */
    public $plan_date;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $type;

    /**
     * @Assert\NotBlank()
     */
    public $priority;


    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $pchelosezonPlem;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    public $personKolChild;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    public $kol;

    public function __construct(string $pchelomatka,
        string $uchastie,
        string $pchelosezonPlem,
        int $maxKolChild)
    {
        $this->pchelomatka = $pchelomatka;
        $this->uchastie = $uchastie;
        $this->pchelosezonPlem = $pchelosezonPlem;
        $this->kolChild = $maxKolChild;
        $this->priority = 2;
        $this->personKolChild = $maxKolChild;
    }
}
