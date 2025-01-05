<?php

declare(strict_types=1);

namespace App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class ChildPcheloRepository
{
    private $repo;
    private $connection;
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(ChildPchelo::class);
        $this->connection = $em->getConnection();
        $this->em = $em;
    }

    /**
     * @param Id $id
     * @return ChildPchelo[]
     */
    public function allByParent(Id $id): array
    {
        return $this->repo->findBy(['parent' => $id->getValue()]);
    }

    public function get(Id $id): ChildPchelo
    {
        /** @var ChildPchelo $childpchelo */
        if (!$childpchelo = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Дочерняя матка не найдена.');
        }
        return $childpchelo;
    }

    public function add(ChildPchelo $childpchelo): void
    {
        $this->em->persist($childpchelo);
    }

    public function remove(ChildPchelo $childpchelo): void
    {
        $this->em->remove($childpchelo);
    }

    public function nextId(): Id
    {
        return new Id((int)$this->connection->query('SELECT nextval(\'admin_pchelo_childs_seq\')')->fetchColumn());
    }
}
