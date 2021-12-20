<?php

namespace App\Form;

use App\Entity\ProfessionalSector;
use App\Entity\School;
use App\Entity\Student;
use App\Entity\StudyLevel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('scholarship', ChoiceType::class, [
                'choices' => [
                    '' => null,
                    'Oui' => true,
                    'Non' => false,
                    'Je ne sais pas' => null,
                ]
            ])
            ->add('dreamJob', TextType::class)
            ->add('dreamDescription', TextareaType::class, [
                'required' => false
                ])
            ->add('topic1', ChoiceType::class, [
                'choices' => [
                    '' => null,
                    "M'immerger dans un métier" => 1,
                    'Me faire coacher' => 2,
                    'Réussir mes candidatures' => 3,
                    'Développer mes compétences' => 4,
                    'Mieux communiquer' => 5,
                    'Mieux gérer les outils digitaux pro' => 6
                ]
            ])
            ->add('topic2', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    "M'immerger dans un métier" => 1,
                    'Me faire coacher' => 2,
                    'Réussir mes candidatures' => 3,
                    'Développer mes compétences' => 4,
                    'Mieux communiquer' => 5,
                    'Mieux gérer les outils digitaux pro' => 6
                ]
                ])
            ->add('topic3', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    "M'immerger dans un métier" => 1,
                    'Me faire coacher' => 2,
                    'Réussir mes candidatures' => 3,
                    'Développer mes compétences' => 4,
                    'Mieux communiquer' => 5,
                    'Mieux gérer les outils digitaux pro' => 6
                ]
                ])
            ->add('professionalSector', EntityType::class, [
                'class' => ProfessionalSector::class,
                'choice_label' => 'name'
                ])
            ->add('school', EntityType::class, [
                'class' => School::class,
                'choice_label' => 'name'
                ])
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
