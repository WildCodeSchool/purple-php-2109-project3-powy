<?php

namespace App\Form;

use App\Entity\Topic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TopicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('topic1', ChoiceType::class, [
            'choices' => Topic::TOPICS,
        ])
        ->add('topic2', ChoiceType::class, [
            'required' => false,
            'choices' => Topic::TOPICS,
            'placeholder' => "----Choix de sujet 2----"
        ])
        ->add('topic3', ChoiceType::class, [
            'required' => false,
            'choices' => Topic::TOPICS,
            'placeholder' => "----Choix de sujet 3----"
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Topic::class,
        ]);
    }
}
