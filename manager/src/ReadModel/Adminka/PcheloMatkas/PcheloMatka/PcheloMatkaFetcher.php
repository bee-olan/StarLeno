<?php

declare(strict_types=1);

namespace App\ReadModel\Adminka\PcheloMatkas\PcheloMatka;

use App\Model\Adminka\Entity\PcheloMatkas\PcheloMatka\PcheloMatka;
use App\ReadModel\Adminka\PcheloMatkas\PcheloMatka\Filter\Filter;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;


class PcheloMatkaFetcher
{
    private $connection;
    private $paginator;
    private $repository;

    public function __construct(Connection $connection, 
                                PaginatorInterface $paginator, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->paginator = $paginator;
        $this->repository = $em->getRepository(PcheloMatka::class);
    }

//    public function find(string $id): ?PcheloMatka
//    {
//        return $this->repository->find($id);
//    }

    public function findPcheloShifr(string $pchelomatka): ?PcheloShifrView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'p.id',
                'p.name',
                'p.title',
                'p.persona_id',
                'p.status',
                'p.mesto_id',
                'p.kategoria_id',
                'p.rasa_id',
                'p.goda_vixod ',
                'p.sort',
                'up.nomer AS persona',
                'dr.name AS rasaname',
                'dr.title AS rasa',
                'm.nomer',
                'r.name AS raion',
                'r.oblast_id ',
                'ob.name AS oblast',
                'ob.okrug_id',
                'ok.name AS okrug',
                'k.name AS kategoria'
//                '(SELECT COUNT(*) FROM admin_pchelomat_pchelosezons d WHERE d.pchelomatka_id = p.id) AS pchsezon_count'
//                '(SELECT COUNT(*) FROM admin_pchelo_childs c WHERE c.pchelomatka_id = p.id) AS child_count'
            )
            ->from('admin_pchelomats', 'p')
            ->innerJoin('p', 'adminka_uchasties_personas', 'up', 'up.id = p.persona_id')
            ->innerJoin('p', 'admin_pchel_kategorias', 'k', 'p.kategoria_id = k.id')
            ->innerJoin('p', 'dre_rass', 'dr', 'dr.id = p.rasa_id')
            ->innerJoin('p', 'mesto_mestonomers', 'm', 'm.id = p.mesto_id')
            ->innerJoin('m', 'mesto_okrug_oblast_raions', 'r', 'r.mesto = m.nomer')
            ->innerJoin('r', 'mesto_okrug_oblasts', 'ob', 'r.oblast_id = ob.id')
            ->innerJoin('ob', 'mesto_okrugs', 'ok', 'ob.okrug_id = ok.id')

            ->where('p.id = :pchelomatka')
            ->setParameter(':pchelomatka', $pchelomatka)
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, PcheloShifrView::class);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function getMaxSort(): int
    {
        return (int)$this->connection->createQueryBuilder()
            ->select('MAX(p.sort) AS m')
            ->from('admin_pchelomats', 'p')

            ->execute()->fetch()['m'];
    }
//    public function infaMesto(string $mesto): array
//    {
//        $stmt = $this->connection->createQueryBuilder()
//            ->select(
//                'r.name AS raion',
//                'ob.name AS oblast',
//                'ok.name AS okrug'
//            )
//            ->from('mesto_okrug_oblast_raions', 'r')
//            ->innerJoin('r', 'mesto_okrug_oblasts', 'ob', 'r.oblast_id = ob.id')
//            ->innerJoin('ob', 'mesto_okrugs', 'ok', 'ob.okrug_id = ok.id')
//
//            ->andWhere('r.mesto = :mesto')
//            ->setParameter(':mesto', $mesto)
//            // ->orderBy('p.sort')->addOrderBy('d.name')
//            ->execute();
//
//        return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
//    }

    /**
     * @param Filter $filter
     * @param int $page
     * @param int $size
     * @param string $sort
     * @param string $direction
     * @return PaginationInterface
     */
    public function allPagin(Filter $filter, int $page, int $size, ?string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'em.id',
                'em.name',
//                'em.persona',
                'em.status'
//                'dm.sort',
//                'pe.nomer as persona'
//                '(SELECT COUNT(*) FROM admin_elitmat_periods s WHERE s.pchelomatka_id = em.id) AS periods_count'
            )
            ->from('admin_pchelomatka', 'em')
//            ->innerJoin('em', 'adminka_uchasties_personas', 'pe', 'em.persona_id = pe.id')

        ;
//        if ($filter->uchastie) {
//            $qb->andWhere('EXISTS (
//                SELECT ms.uchastie_id FROM adminka_matkas_plemmatka_uchastniks ms WHERE ms.plemmatka_id = p.id AND ms.uchastie_id = :uchastie
//            )');
//            $qb->setParameter(':uchastie', $filter->uchastie);
//        }


        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('em.name', ':name'));
            $qb->setParameter(':name', '%' . mb_strtolower($filter->name) . '%');
        }

//        if ($filter->status) {
//            $qb->andWhere('em.status = :status');
//            $qb->setParameter(':status', $filter->status);
//        }

        if (!\in_array($sort, ['name', 'status',  'persona'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function existsUchas(string $id): bool
    {
        return $this->connection->createQueryBuilder()
                ->select('COUNT (persona_id)')
                ->from('admin_pchelomats')
                ->where('persona_id = :id')
                ->setParameter(':id', $id)
                ->execute()->fetchColumn() > 0;
    }

    public function existsPerson(string $id): bool
    {
        return $this->connection->createQueryBuilder()
                ->select('COUNT (id)')
                ->from('adminka_uchasties_personas')
                ->where('id = :id')
                ->setParameter(':id', $id)
                ->execute()->fetchColumn() > 0;
    }

    public function existsMesto(string $id): bool
    {
        return $this->connection->createQueryBuilder()
                ->select('COUNT (id)')
                ->from('mesto_mestonomers')
                ->where('id = :id')
                ->setParameter(':id', $id)
                ->execute()->fetchColumn() > 0;
    }
    /**
     * @param Filter $filter
     * @param int $page
     * @param int $size
     * @param string $sort
     * @param string $direction
     * @return PaginationInterface
     */
    public function all(Filter $filter, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'p.id',
                'p.name',
                'p.title',
                'p.persona_id',
                'p.status',
                'p.sort',
                'p.kategoria_id',
                'p.goda_vixod ',
                'pe.nomer as persona',
//                'k.name AS kategoria',
                '(SELECT COUNT(*) FROM admin_pchelomat_pchelosezons d WHERE d.pchelomatka_id = p.id) AS pchsezon_count'
//                '(SELECT COUNT(*) FROM admin_pchelo_childs c WHERE c.pchelomatka_id = p.id) AS child_count'
            )
            ->from('admin_pchelomats', 'p')
            ->innerJoin('p', 'adminka_uchasties_personas', 'pe', 'p.persona_id = pe.id')
//            ->innerJoin('p', 'admin_matkas_kategorias', 'k', 'p.kategoria_id = k.id')
        ;

        if ($filter->uchastie) {
            $qb->andWhere('EXISTS (
                SELECT ms.uchastie_id FROM admin_pchelomat_pchelovods ms WHERE ms.pchelomatka_id = p.id AND ms.uchastie_id = :uchastie
            )');
            $qb->setParameter(':uchastie', $filter->uchastie);
        }


        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('LOWER(p.name)', ':name'));
            $qb->setParameter(':name', '%' . mb_strtolower($filter->name) . '%');
        }

        if ($filter->status) {
            $qb->andWhere('p.status = :status');
            $qb->setParameter(':status', $filter->status);
        }

        if ($filter->kategoria) {
//            $qb->andWhere($qb->expr()->like('LOWER(p.kategoria)', ':kategoria'));
//            $qb->setParameter(':kategoria', '%' . mb_strtolower($filter->kategoria) . '%');
            $qb->andWhere('k.name = :name');
            $qb->setParameter(':name', $filter->kategoria);
        }

        if ($filter->persona) {
            $qb->andWhere('p.persona = :persona');
            $qb->setParameter(':persona', $filter->persona);
        }

        if ($filter->goda_vixod) {
            $qb->andWhere('p.goda_vixod = :goda_vixod');
            $qb->setParameter(':goda_vixod', $filter->goda_vixod);
        }

        if (!\in_array($sort, ['name','persona', 'kategoria', 'status', 'goda_vixod'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($qb, $page, $size);
    }
}
