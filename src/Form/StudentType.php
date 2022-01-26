<?php

namespace App\Form;

use App\Entity\School;
use App\Entity\Student;
use App\Entity\StudyLevel;
use App\Entity\ProfessionalSector;
use App\Entity\Topic;
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
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Regex;

class StudentType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('scholarship', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                    'Je ne sais pas' => null,
                ],
                'expanded' => true,
                'attr' => [
                    'class' => 'scholarship'
                ]
            ])
            ->add('dreamJob', TextType::class)
            ->add('dreamDescription', TextareaType::class, [
                'required' => false,
            ])
            ->add('professionalSector', EntityType::class, [
                'choice_label' => 'name',
                'class' => ProfessionalSector::class
            ])
            ->add('school', EntityType::class, ['choice_label' => 'name', 'class' => School::class])
            ->add('studyLevel', EntityType::class, [
                'choice_label' => 'name',
                'class' => StudyLevel::class,
                'expanded' => true,
                'attr' => [
                    'class' => 'studyLevel',
                ]
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
            // ->add('topic', TopicType::class)
            ->add('topic', ChoiceType::class, [
                'choices' => Topic::TOPICS,
                'expanded' => true,
                'multiple' => true,
                'constraints' => [
                    new Count(
                        null,
                        1,
                        3,
                        null,
                        "1 à 3 sujets sélectionnables",
                        'Tu dois sélectionner un sujet au minimum',
                        'Seulement 3 sujets peuvent être sélectionnés'
                    )
                ]
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
