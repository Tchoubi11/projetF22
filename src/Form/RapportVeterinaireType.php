<?php

namespace App\Form;

use App\Entity\RapportVeterinaire;
use App\Entity\Animal;
use App\Form\AnimalFeedingType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RapportVeterinaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('animal', EntityType::class, [
                'class' => Animal::class,  
                'choice_label' => 'prenom', 
                'label' => 'Sélectionner un animal',
                'attr' => ['class' => 'form-control'],  
                'required' => true,
                'placeholder'=>'choisissez un animal',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date du rapport',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('habitatComment', TextareaType::class, [
                'label' => 'Commentaires sur l\'habitat',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('feedings', CollectionType::class, [
                'entry_type' => AnimalFeedingType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Alimentation',
                'prototype' => true,
                'prototype_name' => '__name__',
                'attr' => ['class' => 'feedings-collection'],
            ])
            ->add('detail', TextareaType::class, [
                'label' => 'Détails',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer le rapport',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RapportVeterinaire::class, 
        ]);
    }
}
