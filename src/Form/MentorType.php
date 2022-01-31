<?php

namespace App\Form;

use App\Entity\Topic;
use App\Entity\Mentor;
use App\Entity\Company;
use App\Entity\ProfessionalSector;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Regex;

class MentorType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jobTitle', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner un job.'
                    ])]
            ])
            ->add('careerDescription', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseignez une description'
                    ])
                ]
            ])
            ->add('professionalSector', EntityType::class, [
                'choice_label' => 'name',
                'class' => ProfessionalSector::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseignez un secteur professionel'
                    ])
                ]
            ])
            ->add('company', EntityType::class, [
                'choice_label' => 'name',
                'class' => Company::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseignez une entreprise'
                    ])
                ],
                'placeholder' => "Nom de l'entreprise"
            ])
            ->add('companyAdd', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Merci de préciser le nom de l'entreprise"
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
                        'message' => 'Vous devez renseigner un sujet.'
                    ])]
            ])
            ->add('agreeCriminalRecord', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous ne pouvez vous inscrire qu\'en l\'absence de casier judiciaire.',
                    ]),
                ],
            ])
            ->add('agreeChart', CheckboxType::class, [
                'label_html' => true,
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter la charte d\'engagement bénévole.',
                    ]),
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
