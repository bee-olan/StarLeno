<?php

declare(strict_types=1);

namespace App\Menu\Proekts\PageGlavas\PcheloMatka;

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
            ->addChild('Инфа: Пчело-М ', ['route' => 'app.proekts.page_glavas.pchelomatka'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.page_glavas.pchelomatka'],
                ['route' => 'app.proekts.pasekas.pchelomatkas.rasa-pchelos.infa'],
                ['route' => 'app.proekts.pasekas.pchelomatkas.pchelomatka.redaktors.instr'],
                ['pattern' => '/^app.proekts.page_glavas.pchelomatka\..+/'],

            ])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

        $menu
            ->addChild('Список', ['route' => 'app.proekts.pasekas.pchelomatkas.spisoks'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.pasekas.pchelomatkas'],
                ['route' => 'app.proekts.pasekas.pchelomatkas.pchelomatka.show'],
                ['route' => 'app.proekts.pasekas.pchelomatkas.pchelomatka.redaktors.show'],
                ['route' => 'app.proekts.pasekas.pchelomatkas.spisoks'],
                // ['route' => 'app.proekts.page_glavas.pchelomatka.spisok-instr'],
//                ['pattern' => '/^app.proekts.pasekas.pchelomatkas.pchelomatka.redaktors.instr\..+/'],

            ])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

        $menu
            ->addChild('Категории.Трут фон.', ['route' => 'app.proekts.pasekas.pchelomatkas.kategoris.kategor'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.pasekas.pchelomatkas.kategoris.kategor'],
                ['pattern' => '/^app.proekts.pasekas.pchelomatkas.kategoris\..+/']
            ])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

        $menu
            ->addChild('Расы - смотреть', ['route' => 'app.proekts.pasekas.pchelomatkas.rasa-pchelos.rasapchelo'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.pasekas.pchelomatkas.rasa-pchelos.rasapchelo'],
                ['route' => 'app.proekts.pasekas.pchelomatkas.rasa-pchelos.comment.rasacr'],
//                ['pattern' => '/^app.proekts.pasekas.pchelomatkas.kategoris.kategor\..+/']
            ])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');

        $menu
            ->addChild('Регистрация.', ['route' => 'app.proekts.pasekas.pchelomatkas.pchelomatka.creates.create'])
//            ->addChild('Регистрация.', ['route' => 'app.proekts.pasekas.pchelomatkas.creates.create'])
            ->setExtra('routes', [
                ['route' => 'app.proekts.pasekas.pchelomatkas.pchelomatka.creates.create'],
//                ['route' => 'app.proekts.page_glavas.pchelomatka.reg-infa'],
//                ['pattern' => '/^app.proekts.page_glavas.pchelomatka.reg-infa\..+/'],
                ['pattern' => '/^app.proekts.pasekas.pchelomatkas.pchelomatka.creates\..+/']
            ])
            ->setAttribute('class', 'nav_pro-item')
            ->setLinkAttribute('class', 'nav_pro-link');


        return $menu;
    }

}