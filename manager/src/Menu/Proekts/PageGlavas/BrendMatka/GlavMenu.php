<?php

declare(strict_types=1);

namespace App\Menu\Proekts\PageGlavas\BrendMatka;

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
            ->addChild('Инфа: Бренд-М', ['route' => 'app.proekts.page_glavas.brendmatka'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.page_glavas.brendmatka'],
//                ['route' => 'app.proekts.pasekas.matkas'],
                ['pattern' => '/^app.proekts.page_glavas.brendmatka\..+/']
            ])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

        $menu
            ->addChild('Список', ['route' => 'app.proekts.page_glavas.brendmatka'])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

        $menu
            ->addChild('Мат-ка: выбор', ['route' => 'app.proekts.page_glavas.brendmatka'])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

        $menu
            ->addChild('Мат-ка: список', ['route' => 'app.proekts.page_glavas.brendmatka'])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

        $menu
            ->addChild('Отцовские', ['route' => 'app.proekts.page_glavas.brendmatka'])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

        $menu
            ->addChild('Регистрация', ['route' => 'app.proekts.page_glavas.brendmatka'])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

        $menu
            ->addChild('Сезоны', ['route' => 'app.proekts.page_glavas.brendmatka'])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');


//        $menu
//            ->addChild('Список Бр-М', ['route' => 'app.proekts.pasekas.matkas'])
//            ->setExtra('routes', [
//                ['route' => 'app.proekts.pasekas.matkas'],
//                ['route' => 'app.proekts.pasekas.matkas.plemmatkas.show'],
//                ['pattern' => '/^app.proekts.pasekas.matkas.plemmatkas.redaktorss\..+/']
//            ])
//            ->setAttribute('class', 'nav_pro-item')
//            ->setLinkAttribute('class', 'nav_pro-link');



//        $menu
//            ->addChild('Материнка-выбор', ['route' => 'app.proekts.drevorods.rasbrs.plemras'])
//            ->setExtra('routes', [
//                ['route' => 'app.proekts.drevorods.rasbrs'],
//                ['pattern' => '/^app.proekts.drevorods.rasbrs\..+/']
//            ])
//            ->setAttribute('class', 'nav_pro-item')
//            ->setLinkAttribute('class', 'nav_pro-link');
//
//        $menu
//            ->addChild('Материнка-список', ['route' => 'app.proekts.drevorods.rasbrs.spisokDrevos.spisoks'])
//            ->setExtra('routes', [
//                ['route' => 'app.proekts.drevorods.rasbrs'],
//                ['pattern' => '/^app.proekts.drevorods.rasbrs\..+/']
//            ])
//            ->setAttribute('class', 'nav_pro-item')
//            ->setLinkAttribute('class', 'nav_pro-link');
//
//        $menu
//            ->addChild('Отцовские линии', ['route' => 'app.proekts.pasekas.otec_brend.ot_rasas'])
//            ->setExtra('routes', [
//                ['route' => 'app.proekts.pasekas.otec_brend.ot_rasas'],
//                ['pattern' => '/^app.proekts.pasekas.otec_brend.ot_rasas\..+/']
//            ])
//            ->setAttribute('class', 'nav_pro-item')
//            ->setLinkAttribute('class', 'nav_pro-link');
//
//
//
//        $menu
//            ->addChild('Регистрация ', ['route' => 'app.proekts.pasekas.matkas.plemmatkas.creates'])
//            ->setExtra('routes', [
//                ['route' => 'app.proekts.pasekas.matkas.plemmatkas.creates'],
//                ['pattern' => '/^app.proekts.pasekas.matkas.plemmatkas.creates\..+/']
//            ])
//            ->setAttribute('class', 'nav_pro-item')
//            ->setLinkAttribute('class', 'nav_pro-link');
//
//        $menu
//            ->addChild('Сезоны', ['route' => 'app.proekts.pasekas.matkas'])
//            ->setExtra('routes', [
//
//                ['route' => 'app.proekts.pasekas.matkas.plemmatkas.redaktorss'],
////                ['route' => 'app.proekts.pasekas.matkas.plemmatkas.childmatka'],
//                ['pattern' => '/^app.proekts.pasekas.matkas.plemmatkas.childmatka\..+/']
//            ])
//            ->setAttribute('class', 'nav_pro-item')
//            ->setLinkAttribute('class', 'nav_pro-link');


        return $menu;
    }

}