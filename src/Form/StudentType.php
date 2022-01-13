<?php

namespace App\Form;

use App\Entity\School;
use App\Entity\Student;
use App\Entity\StudyLevel;
use App\Entity\ProfessionalSector;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class StudentType extends AbstractType
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
            ->add('scholarship', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                    'Je ne sais pas' => null,
                ]
            ])
            ->add('dreamJob', TextType::class)
            ->add('dreamDescription', TextareaType::class, [
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
            ->add('school', EntityType::class, ['choice_label' => 'name', 'class' => School::class])
            ->add('studyLevel', EntityType::class, [
                'choice_label' => 'name',
                'class' => StudyLevel::class,
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
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caratères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('user', RegistrationFormType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
