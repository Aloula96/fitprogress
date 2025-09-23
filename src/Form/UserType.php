<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Votre nom']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'exemple@email.com']
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Âge',
                'attr' => ['min' => 0, 'max' => 120]
            ])
            ->add('height', IntegerType::class, [
                'label' => 'Taille (cm)',
                'attr' => ['placeholder' => 'Ex: 175']
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Poids (kg)',
                'scale' => 1,
                'attr' => ['placeholder' => 'Ex: 70.5']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
