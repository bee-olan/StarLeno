<?php

declare(strict_types=1);

namespace App\Menu\Proekts\PageGlavas\ElitMatka;

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
            ->addChild('ДочьЭлит', ['route' => 'app.proekts.page_glavas.elitmatka'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.page_glavas.elitmatka'],
                ['pattern' => '/^app.proekts.page_glavas.elitmatka\..+/']
            ])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

//        $menu
//            ->addChild('Список!', ['route' => 'app.proekts.pasekas.childelits'])
//            ->setExtra('routes', [
//                ['route' => 'app.proekts.pasekas.childelits'],
//                ['pattern' => '/^app.proekts.pasekas.childelits\..+/']
//            ])
//            ->setAttribute('class', 'nav_pro-item')
//            ->setLinkAttribute('class', 'nav_pro-link');

        return $menu;
    }

}