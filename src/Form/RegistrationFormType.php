<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner un prénom.'
                    ])]
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner un nom.'
                    ])]
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Féminin' => 'female',
                    'Masculin' => 'male',
                    'Non binaire' => 'non binary',
                    'Ne souhaite pas se prononcer' => null,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un genre',
                    ])
                ]
            ])
            ->add('age', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner un age.'
                    ])]
            ])
            ->add('phone', TelType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner un numéro de téléphone.'
                    ])]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner une adresse mail.'
                    ])]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
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
