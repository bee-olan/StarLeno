<?php

declare(strict_types=1);

namespace App\Menu\Proekts\PageGlavas\UchastGl;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class GlavMenu
{
    private $factory;
    private $auth;

    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $auth)
    {
        $this->factory = $factory;
        $this->auth = $auth;
    }

    public function build(): ItemInterface
    {

        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'nav_pro nav_pro-tabs mb-4']);

            $menu
            ->addChild('Инфа в картинках', ['route' => 'app.proekts.pasekas.uchasties.basepro'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.pasekas.uchasties.basepro'],
                ['route' => 'app.proekts.page_glavas.uchastieGl'],
                // ['route' => 'app.proekts.personaa.create'],
                // ['route' => 'app.proekts.personaa.inform'],
                ['pattern' => '/^app.proekts.page_glavas.uchastieGl\..+/'],
                // ['pattern' => '/^app.proekts.personaa.create\..+/'],
                // ['pattern' => '/^app.proekts.personaa.infa\..+/'],
            ])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

        $menu
            ->addChild('ПерсонНомер', ['route' => 'app.proekts.personaa.infa.infas'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.personaa.infa.infas'],
                ['pattern' => '/^app.proekts.personaa.infa\..+/'],
                ['pattern' => '/^app.proekts.personaa.nomer\..+/'],
            ])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

            $menu
            ->addChild('НомерМеста', ['route' => 'app.proekts.mestos.infa.infas'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.mestos'],
                ['pattern' => '/^app.proekts.mestos\..+/']
            ])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

        $menu
            ->addChild('Стать участником.', ['route' => 'app.proekts.pasekas.uchasties.uchastiee.infa.inform'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.pasekas.uchasties.uchastiee'],
                ['pattern' => '/^app.proekts.pasekas.uchasties.uchastiee\..+/']
            ])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');



        $menu
            ->addChild('Список участников', ['route' => 'app.proekts.pasekas.uchasties.spisok'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.pasekas.uchasties.spisok'],
                ['pattern' => '/^app.proekts.pasekas.uchasties.spisok\..+/']
            ])
            ->setAttribute('class', 'nav_pro-item ')
            ->setLinkAttribute('class', 'nav_pro-link ');

        $menu
            ->addChild('Группы', ['route' => 'app.proekts.pasekas.uchasties.groupas'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.pasekas.uchasties.groupas'],
                ['pattern' => '/^app.proekts.pasekas.uchasties.groupas\..+/']
            ])
            ->setAttribute('class', 'nav_pro-item ')
            ->setLinkAttribute('class', 'nav_pro-link ');

        return $menu;
    }

}