<?php

declare(strict_types=1);

namespace App\Model\Adminka\UseCase\PcheloMatkas\ChildPchelos\Create;

use App\Model\Adminka\Entity\PcheloMatkas\ChildPchelos\Type as ChildPcheloType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder

            ->add('content', Type\TextareaType::class, [
                'label' => '1) Описание Дочь-Пчело  ',
                'required' => false,
                'attr' => ['rows' => 3,
                'placeholder' => ' Подробное описание ...'
                ]])

            ->add('kol', Type\ChoiceType::class, [
                'label' => '2) Сколько (кол-во) дочек объявлять?',
//                'required' => false,
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '5' => 5,
                    '10' => 10
                ],
                'expanded' => true,
                'multiple' => false,
            ])

            ->add('personKolChild', Type\IntegerType::class, [
                            'label' => '3) Нумерация дочек начиная с какого числа ? , но больше ->',
                        ])

            ->add('plan_date', Type\DateType::class, [
                'label' => '4) Дата выхода Дочь-Пчело  ',
                'required' => false, 
                'widget' => 'single_text', 
                'input' => 'datetime_immutable'
                ])
             ->add('type', Type\ChoiceType::class, [
                 'label' => '5) Выбрать вид   облета :   ',
                 'choices' => [
                     'тф-бк' => ChildPcheloType::TFBK,
                     'тф-90' => ChildPcheloType::TF90,
                     'тф-50' => ChildPcheloType::TF50,
             ],
                 'expanded' => true,
                 'multiple' => false,
             ])


            ->add('priority', Type\ChoiceType::class, [
                'label' => '6) Приоритет  предложения дочек   ',
                'choices' => [
                'Низкий' => 1,
                'Обычный' => 2,
                'Высокий' => 3,
                'Срочный' => 4
                ],
                'expanded' => true,
                'multiple' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => Command::class,
        ));
//        $resolver->setRequired(['rasa_id']);
    }
}