<?php

namespace App\Form;

use App\Entity\Gut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class GutType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('reaction', ChoiceType::class, [
                    'choices' => [
                        'Barf' => 'Barf',
                        'Big D' => 'Big D',
                        'Loose' => 'Loose',
                        'Mush' => 'Mush',
                        'Nausea' => 'Nausea',
                        'Pain, gut' => 'Pain, gut',
                    ],
                    'label' => false,
                    'expanded' => true,
                    'multiple' => false,
                ])
                ->add('description', TextareaType::class, [
                    'label' => 'Description',
                ])
                ->add('datetime', DateTimeType::class, [
                    'attr' => ['class' => 'js-datepicker'],
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'input' => 'datetime',
                    'years' => [2022, 2023],
                    'attr' => ['style' => 'width: 300px;'],
                    'placeholder' => '',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gut::class,
        ]);
    }

}
