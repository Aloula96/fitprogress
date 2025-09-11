<?php

namespace App\Form;

use App\Enum\GoalType;
use App\Enum\WorkoutLevel;
use App\Entity\WorkoutPlan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminWorkoutPlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titles', TextType::class)
            ->add('level', ChoiceType::class, [
                'choices' => [
                    'Beginner' => WorkoutLevel::BEGINNER,
                    'Intermediate' => WorkoutLevel::INTERMEDIATE,
                    'Advanced' => WorkoutLevel::ADVANCED,
                ]
            ])
            ->add('description', TextareaType::class)
            ->add('type', ChoiceType::class, [
                'label' => 'Type of plan',
                'choices' => [
                    'Perte de poids' => GoalType::WEIGHT_LOSS,
                    'Prise de masse' => GoalType::MUSCLE_GAIN,
                    'Reprise du sport' => GoalType::RESTART,
                ],
                'choice_label' => fn($choice) => $choice->value,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkoutPlan::class,
        ]);
    }
}
