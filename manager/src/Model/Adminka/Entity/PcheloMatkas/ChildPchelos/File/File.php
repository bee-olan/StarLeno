<?php

declare(strict_types=1);

namespace App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\File;

use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
use App\Model\Adminka\Entity\Uchasties\Uchastie\Uchastie;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="admin_pchelo_child_files", indexes={
 *     @ORM\Index(columns={"date"})
 * })
 */
class File
{
    /**
     * @var ChildPchelo
     * @ORM\ManyToOne(targetEntity="App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo", inversedBy="files")
     * @ORM\JoinColumn(name="childpchelo_id", referencedColumnName="id", nullable=false)
     */
    private $childpchelo;
    /**
     * @var Uchastie
     * @ORM\ManyToOne(targetEntity="App\Model\Adminka\Entity\Uchasties\Uchastie\Uchastie")
     * @ORM\JoinColumn(name="uchastie_id", referencedColumnName="id", nullable=false)
     */
    private $uchastie;
    /**
     * @var Id
     * @ORM\Column(type="admin_pchelo_child_file_id")
     * @ORM\Id
     */
    private $id;
    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;
    /**
     * @var Info
     * @ORM\Embedded(class="Info")
     */
    private $info;

    public function __construct(ChildPchelo $childpchelo, Id $id, Uchastie $uchastie, \DateTimeImmutable $date, Info $info)
    {
        $this->childpchelo = $childpchelo;
        $this->id = $id;
        $this->uchastie = $uchastie;
        $this->date = $date;
        $this->info = $info;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getUchastie(): Uchastie
    {
        return $this->uchastie;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getInfo(): Info
    {
        return $this->info;
    }
}

