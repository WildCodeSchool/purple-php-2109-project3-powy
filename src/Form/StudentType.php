<?php

namespace App\Form;

use App\Entity\ProfessionalSector;
use App\Entity\School;
use App\Entity\Student;
use App\Entity\StudyLevel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('scholarship', TextType::class)
            ->add('dreamJob', TextType::class)
            ->add('dreamDescription', TextareaType::class)
            ->add('topic1', NumberType::class)
            ->add('topic2', NumberType::class)
            ->add('topic3', NumberType::class)
            ->add('professionalSector', EntityType::class, [
                'class' => ProfessionalSector::class,
                'choice_label' => 'name'
                ])
            ->add('school', EntityType::class, ['class' => School::class, 'choice_label' => 'name'])
            ->add('studyLevel', EntityType::class, ['class' => StudyLevel::class, 'choice_label' => 'name'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
