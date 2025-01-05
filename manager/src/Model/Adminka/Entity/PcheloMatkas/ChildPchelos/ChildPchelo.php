<?php

declare(strict_types=1);

namespace App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos;

use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Change\Change;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Change\Id AS ChangeId;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Change\Set;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\File\File;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\File\Id AS FileId;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\File\Info;
use App\Model\Adminka\Entity\PcheloMatkas\PcheloMatka\PcheloMatka;
use App\Model\Adminka\Entity\PcheloMatkas\PcheloMatka\Pchelosezon\Id AS PchelosezonId;
use App\Model\Adminka\Entity\Uchasties\Uchastie\Uchastie;
use App\Model\Adminka\Entity\Uchasties\Uchastie\Id AS UchastieId;
use App\Model\AggregateRoot;
use App\Model\EventsTrait;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="admin_pchelo_childs")
 */
class ChildPchelo

{
    /**
     * @var Id
     * @ORM\Column(type="admin_pchelo_child_id")
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\SequenceGenerator(sequenceName="admin_pchelo_childs_seq", initialValue=1)
     * @ORM\Id
     */
    private $id;
//SEQUENCE  -- NONE

    /**
     * @var PcheloMatka
     * @ORM\ManyToOne(targetEntity="App\Model\Adminka\Entity\PcheloMatkas\PcheloMatka\PcheloMatka")
     * @ORM\JoinColumn(name="pchelomatka_id", referencedColumnName="id", nullable=false)
     */
    private $pchelomatka;

    /**
     * @var Uchastie
     * @ORM\ManyToOne(targetEntity="App\Model\Adminka\Entity\Uchasties\Uchastie\Uchastie")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     */
    private $author;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="date_immutable", nullable=true)
     */
    private $zakazDate;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="date_immutable", nullable=true)
     */
    private $planDate; // запланированная дата

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="date_immutable", nullable=true)
     */
    private $startDate;


    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="date_immutable", nullable=true)
     */
    private $endDate;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @var Type
     * @ORM\Column(type="admin_pchelo_child_type", length=16)
     */
    private $type;


    /**
     * @var ArrayCollection|File[]
     * @ORM\OneToMany(targetEntity="App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\File\File", mappedBy="childpchelo", orphanRemoval=true, cascade={"all"})
     * @ORM\OrderBy({"date" = "ASC"})
     */
    private $files;


    /**
     * @ORM\Column(type="smallint")
     */
    private $priority;

    /**
     * @var ChildPchelo|null
     * @ORM\ManyToOne(targetEntity="ChildPchelo")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $parent;  // родитель

    /**
     * @var Status
     * @ORM\Column(type="admin_pchelo_child_status", length=16)
     */
    private $status;


    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $kolChild;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $godaVixod;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $pchelosezonPlem;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $pchelosezonChild;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $urowni;

     /**
     * @var Uchastie[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Model\Adminka\Entity\Uchasties\Uchastie\Uchastie")
     * @ORM\JoinTable(name="admin_pchelo_child_executors",
     *      joinColumns={@ORM\JoinColumn(name="childpchelo_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="uchastie_id", referencedColumnName="id")}
     * )
     * @ORM\OrderBy({"name.first" = "ASC"})
     */
    private $executors; // экзекутор - исполнитель

    /**
     * @var Change[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Change\Change", mappedBy="childpchelo", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $changes;


    public function __construct(
        Id $id,
        PcheloMatka $pchelomatka,
        Uchastie $author,
        \DateTimeImmutable $date,
        Type $type,
        int $priority,
        string $name,
        ?string $content,
        int $kolChild,
        int $godaVixod,
        string $pchelosezonPlem,
        ?string $pchelosezonChild,
        int $urowni
    )
    {
        $this->id = $id;
        $this->pchelomatka = $pchelomatka;
        $this->author = $author;
        $this->date = $date;
        $this->name = $name;
        $this->content = $content;
        $this->files = new ArrayCollection();
        $this->type = $type;
        $this->priority = $priority;
        $this->status = Status::new();
        $this->kolChild = $kolChild;
        $this->godaVixod = $godaVixod;
        $this->pchelosezonPlem = $pchelosezonPlem;
        $this->pchelosezonChild = $pchelosezonChild;
        $this->urowni = $urowni;
        $this->executors = new ArrayCollection();
        $this->changes = new ArrayCollection();
        $this->addChange($author, $date, Set::forNewChildPchelo($pchelomatka->getId(), $name, $content, $type, $priority,
                                                                $kolChild, $godaVixod, $pchelosezonPlem, $pchelosezonChild, $urowni ));
    }

    public function edit( Uchastie $actor, \DateTimeImmutable $date, string $content): void
    {
        $this->content = $content;

        if ($content !== $this->content) {
            $this->content = $content;
            $this->addChange($actor, $date, Set::fromContent($content));
        }
    }


    public function addFile(Uchastie $actor, \DateTimeImmutable $date, FileId $id, Info $info): void
    {
        $this->files->add(new File($this, $id, $actor, $date, $info));
        $this->addChange($actor, $date, Set::fromFile($id));
    }

    public function removeFile(Uchastie $actor, \DateTimeImmutable $date, FileId $id): void
    {
        foreach ($this->files as $current) {
            if ($current->getId()->isEqual($id)) {
                $this->files->removeElement($current);
                $this->addChange($actor, $date, Set::fromRemovedFile($current->getId()));
                return;
            }
        }
        throw new \DomainException('File is not found.');
    }



    public function zakaz(Uchastie $actor, \DateTimeImmutable $date): void
    {
        if (!$this->isNew()) {
            throw new \DomainException('Матка уже заказана.');
        }
         if (!$this->executors->count()) {
             throw new \DomainException('У матки нет исполнителя.');
         }
        $this->changeStatus($actor, $date, Status::zakaz());

    }

    public function perewodPchelo(Uchastie $actor, \DateTimeImmutable $date): void
    {
        // dd($date);
        if (!$this->isNew() and $this->isPchelosezon()) {
            throw new \DomainException('Матку перевели в ПлемМатку.');
        }
        if (!$this->executors->count()) {
            throw new \DomainException('У матки нет исполнителя.');
        }
        $this->changeStatus($actor, $date, Status::perewodPchelo());
    }

    public function start(Uchastie $actor, \DateTimeImmutable $date): void
    {
       // dd($date);
        if (!$this->isNew() and !$this->isZakaz()) {
            throw new \DomainException('Матка уже заказана.');
        }
        if (!$this->executors->count()) {
            throw new \DomainException('У матки нет исполнителя.');
        }
        $this->changeStatus($actor, $date, Status::working());
    }


    public function setChildOf(Uchastie $actor, \DateTimeImmutable $date,ChildPchelo $parent): void
    {
        if ($parent === $this->parent) {
            return;
        }
        $current = $parent;
        do {
            if ($current === $this) {
                throw new \DomainException('Цикломатические дети.');
            }
        }
        while ($current && $current = $current->getParent());

        $this->parent = $parent;

        $this->addChange($actor, $date, Set::fromParent($parent->getId()));
    }


    public function setRoot(Uchastie $actor, \DateTimeImmutable $date): void
    {
        $this->parent = null;
        $this->addChange($actor, $date, Set::forRemovedParent());
    }

    public function plan(Uchastie $actor, \DateTimeImmutable $date, \DateTimeImmutable $plan): void
    {
        $this->planDate = $plan;
        $this->addChange($actor, $date, Set::fromPlan($plan));

    }

    public function removePlan(Uchastie $actor, \DateTimeImmutable $date): void
    {
        $this->planDate = null;
        $this->addChange($actor, $date, Set::forRemovedPlan());
    }
// переместить
    public function move(Uchastie $actor, \DateTimeImmutable $date, PcheloMatka $pchelomatka): void
    {
        if ($pchelomatka === $this->pchelomatka) {
            throw new \DomainException('PcheloMatka -  таже самая.');
        }
        $this->pchelomatka = $pchelomatka;
         $this->addChange($actor, $date, Set::fromPcheloMatka($pchelomatka->getId()));

    }

//изменить тип
    public function changeType(Uchastie $actor,
                               \DateTimeImmutable $date,
                               Type $type): void
    {
        if ($this->type->isEqual($type)) {
            throw new \DomainException('Тип спаринга - то же самый');
        }
        $this->type = $type;
        $this->addChange($actor, $date, Set::fromType($type));
    }


    public function changeStatus(Uchastie $actor, \DateTimeImmutable $date, Status $status): void
    {
        if ($this->status->isEqual($status)) {
            throw new \DomainException('Статус уже тот же.');
        }
        $this->status = $status;
        $this->addChange($actor, $date, Set::fromStatus($status));

        if (!$status->isNew() && !$this->startDate) {
            $this->startDate = $date;
        }
        if ($status->isDone()) {
//            if ($this->progress !== 100) {
//                $this->changeProgress($actor, $date, 100);
//            }
            $this->endDate = $date;
        } else {
            $this->endDate = null;
        }
    }

    public function changePriority(Uchastie $actor, \DateTimeImmutable $date, int $priority): void
    {
        Assert::range($priority, 1, 4);
        if ($priority === $this->priority) {
            throw new \DomainException('Приоритет уже тот же.');
        }
        $this->priority = $priority;
        $this->addChange($actor, $date, Set::fromPriority($priority));

//        $this->recordEvent(new Event\PcheloMatkaPriorityChanged($actor->getId(), $this->id, $priority));

    }
//-------------  Executor
    public function hasExecutor(UchastieId $id): bool
    {
        foreach ($this->executors as $executor) {
            if ($executor->getId()->isEqual($id)) {
                return true;
            }
        }
        return false;
    }

    public function assignExecutor(Uchastie $actor, \DateTimeImmutable $date, Uchastie $executor): void
    {
        if ($this->executors->contains($executor)) {
            throw new \DomainException('Исполнитель уже назначен.');
        }
        $this->executors->add($executor);
        $this->addChange($actor, $date, Set::fromExecutor($executor->getId()));

    }

    public function revokeExecutor(Uchastie $actor, \DateTimeImmutable $date, UchastieId $id): void
    {
        foreach ($this->executors as $current) {
            if ($current->getId()->isEqual($id)) {
                $this->executors->removeElement($current);
                $this->addChange($actor, $date, Set::fromRevokedExecutor($current->getId()));
                return;
            }
        }
        throw new \DomainException('тесер откреплен от матки.');
    }

//------------- end Executor

// равно Ли Имя
    public function isPchelosezonEqual(string $pchelosezon): bool
    {
        return $this->getPchelosezonPlem() === $pchelosezon;
    }

    public function idPchelosezon($pchelosezonFetcher): PchelosezonId
    {
        foreach ($pchelosezonFetcher as $key => $value) {

            if ($this->isPchelosezonEqual($value)) {
                $idDeppart = $key;
                return new PchelosezonId($idDeppart ) ;
                break;
            }
        }
        throw new \DomainException('сезон не найден.');
    }

    public function isNew(): bool
    {
        return $this->status->isNew();
    }

    public function isPchelosezon(): bool
    {
        return $this->status->isPchelosezon();
    }

    public function isZakaz(): bool
    {
        return $this->status->isZakaz();
    }

    public function isWorking(): bool
    {
        return $this->status->isWorking();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getPcheloMatka(): PcheloMatka
    {
        return $this->pchelomatka;
    }

    public function getAuthor(): Uchastie
    {
        return $this->author;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getZakazDate(): ?\DateTimeImmutable
    {
        return $this->zakazDate;
    }

    public function getPlanDate(): ?\DateTimeImmutable
    {
        return $this->planDate;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getType(): Type
    {
        return $this->type;
    }


    public function getPriority(): int
    {
        return $this->priority;
    }


    public function getParent(): ?ChildPchelo
    {
        return $this->parent;
    }


    public function getStatus(): Status
    {
        return $this->status;
    }


    /**
     * @return Uchastie[]
     */
    public function getExecutors(): array
    {
        return $this->executors->toArray();
    }

    public function getExecutor(UchastieId $id): Uchastie
    {
        foreach ($this->executors as $executor) {
            if ($executor->getId()->isEqual($id)) {
                return $executor;
            }
        }
        throw new \DomainException('тестер  не найден.');
    }

    public function getKolChild(): int
    {
        return $this->kolChild;
    }


    public function getGodaVixod(): int
    {
        return $this->godaVixod;
    }


    public function getPchelosezonPlem(): ?string
    {
        return $this->pchelosezonPlem;
    }


    public function getPchelosezonChild(): ?string
    {
        return $this->pchelosezonChild;
    }

    public function getUrowni(): int
    {
        return $this->urowni;
    }


    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files->toArray();
    }

     /**
      * @return Change[]
      */
     public function getChanges(): array
     {
         return $this->changes->toArray();
     }

     private function addChange(Uchastie $actor, \DateTimeImmutable $date, Set $set): void
     {
         if ($last = $this->changes->last()) {
             /** @var Change $last */
             $next = $last->getId()->next();
         } else {
             $next = ChangeId::first();
         }
         $this->changes->add(new Change($this, $next, $actor, $date, $set));
     }
}
