<?php

declare(strict_types=1);

namespace App\ReadModel\Adminka\PcheloMatkas\Actions;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Query\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;

class ActionFetcher
{
    private $connection;
    private $paginator;

    public function __construct(Connection $connection, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->paginator = $paginator;
    }

    public function all(Filter $filter, int $page, int $size): PaginationInterface
    {
        $qb = $this->createQb();

        if ($filter->uchastie) {
            $qb->innerJoin('pchelomatka', 'admin_pchelomat_sostavs', 'sostav', 'pchelomatka.id = sostav.pchelomatka_id');
            $qb->andWhere('sostav.uchastie_id = :uchastie');
            $qb->setParameter(':uchastie', $filter->uchastie);
        }

        if ($filter->pchelomatka) {
            $qb->andWhere('pchelomatka.id = :pchelomatka_id OR set_pchelomatka.id = :pchelomatka_id');
            $qb->setParameter(':pchelomatka_id', $filter->pchelomatka);
        }

        $qb->orderBy('c.date', 'desc');

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function allForChildPchelo(int $id): array
    {
        $stmt = $this->createQb()
            ->andWhere('childpchelo.id = :childpchelo_id')
            ->setParameter(':childpchelo_id', $id)
            ->orderBy('c.date', 'asc')
            ->execute();

        return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
    }

    private function createQb(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(
                'c.*',
                'childpchelo.name AS childpchelo_name',
                'TRIM(CONCAT(actor.name_first, \' \', actor.name_last)) AS actor_name',
                'actor.nike AS actor_nike',
                'pchelomatka.id AS pchelomatka_id',
                'pchelomatka.name AS pchelomatka_name',
                'TRIM(CONCAT(set_executor.name_first, \' \', set_executor.name_last)) AS set_executor_name',
                'set_executor.nike AS set_executor_nike',
                'TRIM(CONCAT(set_revoked_executor.name_first, \' \', set_revoked_executor.name_last)) AS set_revoked_executor_name',
                'set_revoked_executor.nike AS set_revoked_executor_nike',
                'set_pchelomatka.name AS set_pchelomatka_name'
            )
            ->from('admin_pchelo_child_changes', 'c')
            ->leftJoin('c', 'admin_uchasties_uchasties', 'actor', 'c.actor_id = actor.id')
            ->leftJoin('c', 'admin_uchasties_uchasties', 'set_executor', 'c.set_executor_id = set_executor.id')
            ->leftJoin('c', 'admin_uchasties_uchasties', 'set_revoked_executor', 'c.set_revoked_executor_id = set_executor.id')
            ->leftJoin('c', 'admin_pchelo_childs', 'childpchelo', 'c.childpchelo_id = childpchelo.id')
            ->leftJoin('childpchelo', 'admin_pchelomats', 'pchelomatka', 'childpchelo.pchelomatka_id = pchelomatka.id')
            ->leftJoin('c', 'admin_pchelomats', 'set_pchelomatka', 'c.set_pchelomatka_id = set_pchelomatka.id');
    }



}
