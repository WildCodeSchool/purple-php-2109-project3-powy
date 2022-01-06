<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'input'
                ],
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'input'
                ],
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'input'
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'attr' => [
                    'class' => 'input'
                ],
                'choices' => [
                    'Féminin' => 'female',
                    'Masculin' => 'male',
                    'Non binaire' => 'non binary',
                    'Ne souhaite pas se prononcer' => null,
                ]
            ])
            ->add('age', IntegerType::class, [
                'attr' => [
                    'class' => 'input'
                ],
            ])
            ->add('phone', IntegerType::class, [
                'attr' => [
                    'class' => 'input'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
