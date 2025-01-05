<?php

declare(strict_types=1);

namespace App\Model\Adminka\Entity\Sezons\Godas;

use App\Model\EntityNotFoundException;
use App\ReadModel\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class GodaRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Goda::class);
        $this->em = $em;
    }

    public function getGogaId(int $god): Id
    {
        /** @var Goda $goda */
        if (!$goda = $this->repo->findOneBy(['god' => $god])) {
            throw new EntityNotFoundException('Нет такой ПлемМатки.');
        }
        return $goda->getId();
    }

    public function get(Id $id): Goda
    {
        /** @var Goda $goda */
        if (!$goda = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('Нет такого сезона.');
        }
        return $goda;
    }

    public function getGodaName(int $god): Goda
    {
        /** @var Goda $goda */
        if (!$goda = $this->repo->findOneBy(['god' => $god])) {
            throw new EntityNotFoundException('Нет такого года!!!!!!!!!!.');
        }
        return $goda;
    }

    public function remove(Goda $goda): void
    {
        $this->em->remove($goda);
    }

    public function add(Goda $goda): void
    {
        $this->em->persist($goda);
    }
}
