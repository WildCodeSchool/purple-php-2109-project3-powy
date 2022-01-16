<?php

namespace App\Form;

use App\Entity\Mentor;
use App\Entity\Company;
use App\Entity\ProfessionalSector;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class MentorType extends AbstractType
{
    private const TOPICS = [
        "M'immerger dans un métier" => 1,
        'Me faire coacher' => 2,
        'Réussir mes candidatures' => 3,
        'Développer mes compétences' => 4,
        'Mieux gérer les outils digitaux pro' => 5,
        'Mieux communiquer en français' => 6,
        'Mieux communiquer en anglais' => 7,
        'Mieux communiquer en espagnol' => 8,
        'Mieux communiquer en allemand' => 9,

    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jobTitle', TextType::class)
            ->add('careerDescription', TextareaType::class, [
                'required' => false,
            ])
            ->add('topic1', ChoiceType::class, [
                'choices' => self::TOPICS,
            ])
            ->add('topic2', ChoiceType::class, [
                'required' => false,
                'choices' => self::TOPICS,
                ])
            ->add('topic3', ChoiceType::class, [
                'required' => false,
                'choices' => self::TOPICS,
                ])
            ->add('professionalSector', EntityType::class, [
                'choice_label' => 'name',
                'class' => ProfessionalSector::class
            ])
            ->add('company', EntityType::class, [
                'choice_label' => 'name',
                'class' => Company::class
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un mot de passe.',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caratères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d](?=.*?([^\w\s]|[_])).{8,}$/',
                        'message' =>
                        "Votre mot de passe doit contenir au moins un chiffre, une majuscule et un caractère spécial.",
                    ])
                ],
            ])
            ->add('user', RegistrationFormType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mentor::class,
        ]);
    }
}
