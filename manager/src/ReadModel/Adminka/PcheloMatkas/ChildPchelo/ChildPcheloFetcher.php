<?php

declare(strict_types=1);

namespace App\ReadModel\Adminka\PcheloMatkas\ChildPchelo;

use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\ChildPchelo;
use App\Model\Adminka\Entity\Uchasties\Uchastie\Uchastie;
use App\Model\Adminka\Entity\Uchasties\Uchastie\Id AS UchastieId;
use App\ReadModel\Adminka\PcheloMatkas\ChildPchelo\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ChildPcheloFetcher
{
    private $connection;
    private $paginator;
    private $repository;

    public function __construct(Connection $connection, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->paginator = $paginator;
        $this->repository = $em->getRepository(ChildPchelo::class);
    }

    public function find(string $id): ?ChildPchelo
    {
        return $this->repository->find($id);
    }

    public function getMaxKolChild(string $pchelomatka, string $sezonPlem): int
    {
        return (int)$this->connection->createQueryBuilder()
            ->select('MAX(c.kol_child) AS m')
            ->from('admin_pchelo_childs', 'c')
            ->andWhere('pchelomatka_id = :pchelomatka AND pchelosezon_plem = :sezonPlem')
            ->setParameter(':pchelomatka', $pchelomatka)
            ->setParameter(':sezonPlem', $sezonPlem)
            ->execute()->fetch()['m'];
    }

    public function getExecutorChild(int $childpchelo): string
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'childpchelo_id',
                'uchastie_id'
            )
            ->from('admin_pchelo_child_executors')
            ->andWhere('childpchelo_id = :childpchelo')
            ->setParameter(':childpchelo', $childpchelo)
            ->orderBy('uchastie_id')
            ->execute();
        $result = $stmt->fetch();
        return $result['uchastie_id'];
    }

//    public function listOfPcheloMatka(int $childpchelo): array
//    {
//
//        $stmt = $this->connection->createQueryBuilder()
//            ->select(
//                'childpchelo_id',
//                'uchastie_id'
//            )
//            ->from('admin_pchelo_childs_executors')
//            ->andWhere('childpchelo_id = :childpchelo')
//            ->setParameter(':childpchelo', $childpchelo)
//            ->orderBy('name')
//            ->execute();
//        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
//    }

    public function AllExecutorChild(): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'childpchelo_id',
                'uchastie_id'
            )
            ->from('admin_pchelo_childs_executors')
            ->orderBy('childpchelo_id')
            ->execute();

        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function listZakazForTochka(string $uchastie): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'e.childpchelo_id AS id',
                'e.uchastie_id',
                'm.name AS name',
                'm.start_date AS god_test'
            )
            ->from('admin_pchelo_child_executors', 'e')
            ->innerJoin('e', 'admin_pchelo_childs', 'm', 'e.childpchelo_id = m.id')
//            ->innerJoin('e', 'admin_sezons_godas', 'g', 'e.childpchelo_id = m.id')
            ->andWhere('e.uchastie_id = :uchasties')
            ->setParameter(':uchasties', $uchastie)
            // ->orderBy('d.name')->addOrderBy('name')
            ->execute();
        return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
    }


    /**
     * @param Filter $filter
     * @param int $page
     * @param int $size
     * @param string $sort
     * @param string $direction
     * @return PaginationInterface
     */
    public function all(Filter $filter, int $page, int $size, ?string $sort, ?string $direction): PaginationInterface
    {
        if (!\in_array($sort, [null,   't.id', 't.pchelosezon_plem', 't.date',  'author_name', 'pchelomatka_name', 'name', 't.type', 't.plan_date', 't.priority','t.urowni', 't.status'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb = $this->connection->createQueryBuilder()
            ->select(
                't.id',
                't.pchelomatka_id',
                't.author_id',
                't.date',
                'm.nike AS  author_name',
                'p.name AS pchelomatka_name',
                'p.sort AS pchelomatka_sort',
                'p.korotko_name AS korotko',
                'p.goda_vixod AS pchelomatka_god',
                't.name',
                't.content',
                't.parent_id AS parent',
                't.type',
                't.priority',
                't.plan_date',
                't.status',
                't.pchelosezon_plem ',
                't.urowni',
                'r.nomer AS mesto',
                'u.nomer AS  author_persona'

            )
            ->from('admin_pchelo_childs', 't')
            ->innerJoin('t', 'admin_uchasties_uchasties', 'm', 't.author_id = m.id')
            ->innerJoin('t', 'admin_pchelomats', 'p', 't.pchelomatka_id = p.id')
            ->innerJoin('t', 'mesto_mestonomers', 'r', 't.author_id = r.id')
            ->innerJoin('t', 'adminka_uchasties_personas', 'u', 't.author_id = u.id')
//            ->innerJoin('t', 'admin_pchelo_child_executors', 'e', 'e.childpchelo_id = t.id')
        ;

        if ($filter->uchastie) {
            $qb->innerJoin('t', 'admin_pchelomat_pchelovods', 'ms', 't.pchelomatka_id = ms.pchelomatka_id');
            $qb->andWhere('ms.uchastie_id = :uchastie');
            $qb->setParameter(':uchastie', $filter->uchastie);
        }

//        if ($filter->pchelomatka) {
//            $qb->andWhere('t.pchelomatka_id = :pchelomatka');
//            $qb->setParameter(':pchelomatka', $filter->pchelomatka);
//        }

        if ($filter->author) {
            $qb->andWhere('t.author_id = :author');
            $qb->setParameter(':author', $filter->author);
        }


        if ($filter->text) {
            $vector = "(setweight(to_tsvector(t.name),'A') || setweight(to_tsvector(coalesce(t.content,'')), 'B'))";
            $query = 'plainto_tsquery(:text)';
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('LOWER(CONCAT(t.name, \' \', coalesce(t.content, \'\')))', ':text'),
                "$vector @@ $query"
            ));
            $qb->setParameter(':text', '%' . mb_strtolower($filter->text) . '%');
            if (empty($sort)) {
                $sort = "ts_rank($vector, $query)";
                $direction = 'desc';
            }
        }

        if ($filter->type) {
            $qb->andWhere('t.type = :type');
            $qb->setParameter(':type', $filter->type);
        }

        if ($filter->priority) {
            $qb->andWhere('t.priority = :priority');
            $qb->setParameter(':priority', $filter->priority);
        }

        if ($filter->status) {
            $qb->andWhere('t.status = :status');
            $qb->setParameter(':status', $filter->status);
        }

         if ($filter->executor) {
             $qb->innerJoin('t', 'admin_pchelo_child_executors', 'e', 'e.childpchelo_id = t.id');
             $qb->andWhere('e.uchastie_id = :executor');
             $qb->setParameter(':executor', $filter->executor);
         }

        if ($filter->roots) {
            $qb->andWhere('t.parent_id IS NULL');
        }

        if ($filter->urowni) {
            $qb->andWhere('t.urowni > :urowni');
          $qb->setParameter(':urowni', $filter->urowni);
        }

        if (!$sort) {
//            $sort = 't.pchelosezon_plem';
            $sort = 't.id';
            $direction = $direction ?: 'desc';
        } else {
            $direction = $direction ?: 'asc';
        }

        $qb->orderBy($sort, $direction);


        $pagination = $this->paginator->paginate($qb, $page, $size);

        $childpchelos = (array)$pagination->getItems();
        $executors = $this->batchLoadExecutors(array_column($childpchelos, 'id'));

        $pagination->setItems(array_map(static function (array $childpchelo) use ($executors) {
            return array_merge($childpchelo, [
                'executors' => array_filter($executors, static function (array $executor) use ($childpchelo) {
                    return $executor['childpchelo_id'] === $childpchelo['id'];
                }),
            ]);
        }, $childpchelos));

        return $pagination;
    }

     public function childrenOf(int $childpchelo): array
     {
         $stmt = $this
             ->connection->createQueryBuilder()
             ->select(
                 't.id',
                 't.date',
                 't.pchelomatka_id',
                 't.author_id',
                 'm.nike AS  author_name',
                 'p.name AS pchelomatka_name',
                 't.name',
                 't.content',
                 't.parent_id AS parent',
                 't.type',
                 't.priority',
                 't.plan_date',
                 't.status'
             )
             ->from('admin_pchelo_childs', 't')
             ->innerJoin('t', 'admin_uchasties_uchasties', 'm', 't.author_id = m.id')
             ->innerJoin('t', 'admin_pchelomats', 'p', 't.pchelomatka_id = p.id')
             ->andWhere('t.parent_id = :parent')
             ->setParameter(':parent', $childpchelo)
             ->orderBy('date', 'desc')
             ->execute();

         $childpchelos = $stmt->fetchAll(FetchMode::ASSOCIATIVE);
//         dd($childpchelos);
         $executors = $this->batchLoadExecutors(array_column($childpchelos, 'id'));

         return array_map(static function (array $childpchelo) use ($executors) {
             return array_merge($childpchelo, [
                 'executors' => array_filter($executors, static function (array $executor) use ($childpchelo) {
                     return $executor['childpchelo_id'] === $childpchelo['id'];
                 }),
             ]);
         }, $childpchelos);
     }

     private function batchLoadExecutors(array $ids): array
     {
         $stmt = $this->connection->createQueryBuilder()
             ->select(
                 'e.childpchelo_id',
//                 'TRIM(CONCAT(m.name_first, \' \', m.name_last)) AS name'
                 'm.nike AS name'
             )
             ->from('admin_pchelo_child_executors', 'e')
             ->innerJoin('e', 'admin_uchasties_uchasties', 'm', 'm.id = e.uchastie_id')
             ->andWhere('e.childpchelo_id IN (:childpchelo)')
             ->setParameter(':childpchelo', $ids, Connection::PARAM_INT_ARRAY)
             ->orderBy('name')
             ->execute();

         return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
     }

}
