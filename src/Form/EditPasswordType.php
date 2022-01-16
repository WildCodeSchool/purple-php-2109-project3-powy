<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Regex;

class EditPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'attr' => ['
                    autocomplete' => 'new-password',
                    'class' => 'input'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir un nouveau de mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit avoir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d](?=.*?([^\w\s]|[_])).{8,}$/',
                        'message' =>
                        "Votre mot de passe doit contenir au moins un chiffre, une majuscule et un caractère spécial.",
                    ])
                ],
                'label' => 'Nouveau mot de passe',
            ],
            'second_options' => [
                'attr' => ['
                    autocomplete' => 'new-password',
                    'class' => 'input'],
                'label' => 'Répéter mot de passe',
            ],
            'invalid_message' => 'Les deux mots de passe doivent être identiques',
            // Instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
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
