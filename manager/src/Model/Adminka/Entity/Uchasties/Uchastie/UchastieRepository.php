<?php

declare(strict_types=1);

namespace App\Model\Adminka\Entity\Uchasties\Uchastie;

use App\Model\EntityNotFoundException;
use App\Model\Adminka\Entity\Uchasties\Group\Id as GroupId;
use Doctrine\ORM\EntityManagerInterface;

class UchastieRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Uchastie::class);
        $this->em = $em;
    }

    public function has(Id $id): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.id = :id')
                ->setParameter(':id', $id->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function hasByGroup(GroupId $id): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.group = :group')
                ->setParameter(':group', $id->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function get(Id $id): Uchastie
    {
        /** @var Uchastie $uchastie */
        if (!$uchastie = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Участие не найдено.');
        }
        return $uchastie;
    }

    public function add(Uchastie $uchastie): void
    {

        $this->em->persist($uchastie);
    }
}
