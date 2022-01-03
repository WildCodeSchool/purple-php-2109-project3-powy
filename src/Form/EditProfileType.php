<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
