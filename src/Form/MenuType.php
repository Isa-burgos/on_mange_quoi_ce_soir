<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Recipe;
use App\Enum\MealTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('meal_date', null, [
                'widget' => 'single_text',
            ])
            ->add('meal_time', EnumType::class, [
                'class' => MealTime::class,
                'choice_label' => fn($choice) => match($choice) {
                    MealTime::PETIT_DEJEUNER => "petit_dejeuner",
                    MealTime::DEJEUNER => 'dejeuner',
                    MealTime::GOUTER => 'gouter',
                    MealTime::DINER => 'diner',
        },
            ])
            ->add('recipes', EntityType::class, [
                'class' => Recipe::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
