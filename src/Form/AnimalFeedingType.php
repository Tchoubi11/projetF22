<?php
// src/Form/FeedingType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use APP\Entity\AnimalFeeding;

class AnimalFeedingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'attr' => [
                    'placeholder' => 'jj/mm/aaaa',
                ],
            ])
            ->add('nourriture', TextType::class, [
                'label' => 'Nourriture',
                'attr' => [
                    'placeholder' => 'Entrez la nourriture',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnimalFeeding::class,  // Pas besoin de lier ce formulaire à une entité spécifique
        ]);
    }
}

