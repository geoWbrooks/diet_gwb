<?php

namespace App\Form;

use App\Entity\Gut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GutType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('description', TextType::class, [
                    'label' => 'Description',
                ])
                ->add('datetime', DateTimeType::class, [
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
