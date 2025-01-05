<?php

namespace App\ReadModel\Adminka\PcheloMatkas\ChildPchelo\Filter;

use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Type as ChildPcheloType;
use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Status;

use App\ReadModel\Adminka\Uchasties\Uchastie\UchastieFetcher;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    private $uchasties;

    public function __construct(UchastieFetcher $uchasties)
    {
        $this->uchasties = $uchasties;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $uchasties = [];
        foreach ($this->uchasties->activeGroupedList() as $item) {
            $uchasties[$item['group']][$item['name']] = $item['id'];
        }

        $builder
            ->add('text', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Поиск...',
                'onchange' => 'this.form.submit()',
            ]])
             ->add('type', Type\ChoiceType::class, ['choices' => [
                 'тф-бк' => ChildPcheloType::TFBK,
                 'тф-90' => ChildPcheloType::TF90,
                 'тф-50' => ChildPcheloType::TF50,
             ], 'required' => false, 'placeholder' => 'Спаринг', 'attr' => ['onchange' => 'this.form.submit()']])

            ->add('status', Type\ChoiceType::class, ['choices' => [
                'новая' => Status::NEW,
                'Заказана' => Status::ZAKAZ ,
                'в работе' => Status::WORKING,
                'вопрос' => Status::HELP,
                'отклонена' => Status::REJECTED,
                'завершено' => Status::DONE,
            ], 
            'required' => false, 
            'placeholder' => 'Статусы', 
            'attr' => ['onchange' => 'this.form.submit()']])

            ->add('priority', Type\ChoiceType::class, ['choices' => [
                'Низкий' => 1,
                'Обычный' => 2,
                'Высокий' => 3,
                'Срочный' => 4
            ],
                'required' => false,
                'placeholder' => 'Приоритеты',
                'attr' => ['onchange' => 'this.form.submit()']
            ])

            ->add('urowni', Type\ChoiceType::class, [
                'choices' => [
                'Все уровни' => 0,
                'уровень >= 1' => 1,
                'уровень >= 2' => 2,
                'уровень >= 3' => 3,
                'уровень >= 4' => 4,
                'уровень >= 5' => 5
            ],
            'required' => false,
            'placeholder' => 'Уровень влож',
            'attr' => ['onchange' => 'this.form.submit()']
            ])

            ->add('author', Type\ChoiceType::class, [
                'choices' => $uchasties,
                'required' => false,
                'placeholder' => 'Авторы',
                'attr' => ['onchange' => 'this.form.submit()']
            ])
            ->add('executor', Type\ChoiceType::class, [
                'choices' => $uchasties,
                'required' => false,
                'placeholder' => 'Тестер',
                'attr' => ['onchange' => 'this.form.submit()']
            ])
             ->add('roots', Type\ChoiceType::class, ['choices' => [
                 'Только родители' => Status::NEW,
             ], 'required' => false,
                'placeholder' => 'Все уровни',
                'attr' => ['onchange' => 'this.form.submit()']])
        ;
     }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
