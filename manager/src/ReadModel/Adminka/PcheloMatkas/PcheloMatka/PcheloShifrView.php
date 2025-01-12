<?php

declare(strict_types=1);

namespace App\ReadModel\Adminka\PcheloMatkas\PcheloMatka;

class PcheloShifrView
{
    public $id;
    public $name;
    public $title;
    public $okrug;
    public $oblast;
    public $raion;
    public $nomer;

    public $persona;
//    public $sort;
    public $status;

    /**
     * @return mixed
     */
    public function getOblast()
    {
        return $this->oblast;
    }



}
