<?php

declare(strict_types=1);

namespace App\DataFixtures\Sezons\Godas;

use App\Model\Adminka\Entity\Sezons\Godas\Id;
use App\Model\Adminka\Entity\Sezons\Godas\Goda;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GodaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
//        $sezon2015 = new Goda(
//            Id::next(),
//            2008,
//            '2008-2009'
//        );
//        $manager->persist($sezon2015);
        $igodd = 2007;
        for ($i = 1; $i < 17; $i++) {
            $igod = $igodd + $i;
            $sez = $igod . '-' . ($igod + 1);
            $sezon = new Goda(
                Id::next(),
                (int)$igod,
                $sez
            );
            $manager->persist($sezon);
        }
        $manager->flush();
    }

}