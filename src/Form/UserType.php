<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('studyLevel', StudyLevelType::class)
            ->add('school', SchoolType::class)
            ->add('student', StudentType::class)
            ->add('professionalSector', ProfessionalSectorType::class)
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('firstname')
            ->add('lastname')
            ->add('gender')
            ->add('age')
            ->add('phone')
            ->add('isVerified')
            ->add('student')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
