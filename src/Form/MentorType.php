<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Mentor;
use App\Entity\ProfessionalSector;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MentorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jobTitle', TextType::class)
            ->add('careerDescription', TextareaType::class, [
                'required' => false,
            ])
            ->add('professionalSector', EntityType::class, [
                'choice_label' => 'name',
                'class' => ProfessionalSector::class
            ])
            ->add('company', EntityType::class, [
                'choice_label' => 'name',
                'class' => Company::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mentor::class,
        ]);
    }
}
