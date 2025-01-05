<?php

declare(strict_types=1);

namespace App\Menu\Proekts\PageGlavas\BrendMatka;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ChildMenu
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
            ->addChild('ДочьБренд', ['route' => 'app.proekts.page_glavas.brendmatka'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.page_glavas.brendmatka'],
//                ['pattern' => '/^app.proekts.pasekas.matkas.plemmatkas.childmatka\..+/'],
                ['pattern' => '/^app.proekts.page_glavas.brendmatka\..+/']
            ])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

//        $menu
//            ->addChild('Список ДочьМаток', ['route' => 'app.proekts.pasekas.childmatkas'])
//            ->setExtra('routes', [
//                ['route' => 'app.proekts.pasekas.childmatkas'],
//                ['pattern' => '/^app.proekts.pasekas.childmatkas\..+/']
//            ])
//            ->setAttribute('class', 'nav_pro-item')
//            ->setLinkAttribute('class', 'nav_pro-link');
//
//        $menu
//            ->addChild('Список БрендМаток', ['route' => 'app.proekts.pasekas.matkas'])
//            ->setExtra('routes', [
//                ['route' => 'app.proekts.pasekas.matkas'],
//            ])
//            ->setAttribute('class', 'nav_pro-item')
//            ->setLinkAttribute('class', 'nav_pro-link');

        return $menu;
    }

}