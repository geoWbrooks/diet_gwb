<?php

namespace App\Form;

use App\Entity\Meal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

//use Doctrine\ORM\EntityRepository;

class MealType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('meal_type', ChoiceType::class, [
                    'choices' => [
                        'Breakfast' => 'Breakfast',
                        'Lunch' => 'Lunch',
                        'Dinner' => 'Dinner',
                    ],
                    'label' => false,
                    'expanded' => true,
                    'multiple' => false,
                ])
                ->add('date', DateType::class, [
                    'label' => 'Date ',
                    'widget' => 'single_text',
                    'format' => 'yyyy-mm-dd',
                    'html5' => false,
                    'attr' => [
                        'class' => 'js-datepicker',
                    ],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meal::class,
            'pagination' => null,
        ]);
    }

}
