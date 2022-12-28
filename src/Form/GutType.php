<?php

namespace App\Form;

use App\Entity\Gut;
use App\Entity\Reaction;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GutType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('reaction', EntityType::class, [
                    'class' => Reaction::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('r')
                        ->orderBy('r.reaction', 'ASC');
                    },
                    'choice_label' => 'Reaction',
                ])
                ->add('description', TextareaType::class, [
                    'label' => 'Comment',
                ])
                ->add('happened', DateTimeType::class, [
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
