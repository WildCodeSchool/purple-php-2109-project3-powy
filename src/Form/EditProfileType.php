<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('firstname', TypeTextType::class, [
                'attr' => [
                    'class' => 'input-firstname'
                ],
            ])
            ->add('lastname', TypeTextType::class, [
                'attr' => [
                    'class' => 'input-lastname'
                ],
            ])
            ->add('gender', TypeTextType::class, [
                'attr' => [
                    'class' => 'input-gender'
                ],
            ])
            ->add('age', IntegerType::class, [
                'attr' => [
                    'class' => 'input-age'
                ],
            ])
            ->add('phone', IntegerType::class, [
                'attr' => [
                    'class' => 'input-age'
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
