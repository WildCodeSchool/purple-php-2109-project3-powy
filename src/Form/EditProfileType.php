<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

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
            ->add('phone', TelType::class, [
                'attr' => [
                    'class' => 'input'
                ],
            ])
            ->add('picture', FileType::class, [
                'label' => 'Picture (PNG, JPEG, JPG)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PNG, JPEG, JPG picture',
                    ])
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
