<?php

namespace App\Form;

use App\Entity\ProfessionalSector;
use App\Entity\School;
use App\Entity\Student;
use App\Entity\StudyLevel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                ]
            ])
            ->add('dreamJob', TextType::class)
            ->add('dreamDescription', TextareaType::class)
            ->add('professionalSector', EntityType::class, [
                'choice_label' => 'name',
                'class' => ProfessionalSector::class
            ])
            ->add('school', EntityType::class, ['choice_label' => 'name', 'class' => School::class])
            ->add('studyLevel', EntityType::class, [
                'choice_label' => 'name',
                'class' => StudyLevel::class,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
