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
            ->add('dreamJob', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner votre niveau.'
                    ])]
            ])
            ->add('dreamDescription', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner une description.'
                    ])]
            ])
            ->add('professionalSector', EntityType::class, [
                'choice_label' => 'name',
                'class' => ProfessionalSector::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner votre niveau professionel.'
                    ])]
            ])
            ->add('school', EntityType::class, [
                'choice_label' => 'name',
                'class' => School::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner une école'
                    ])
                ],
                'placeholder' => 'Choisis une école'
            ])
            ->add('schoolAdd', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Merci de préciser le nom de l'établissement"
                ]
            ])
            ->add('studyLevel', EntityType::class, [
                'choice_label' => 'name',
                'class' => StudyLevel::class,
                'expanded' => true,
                'attr' => [
                    'class' => 'studyLevel',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner une niveau d\'étude'
                    ])
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
            ->add('topic', TopicType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner un numéro de téléphone.'
                    ])]
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
