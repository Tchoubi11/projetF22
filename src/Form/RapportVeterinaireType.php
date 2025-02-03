<?php

namespace App\Form;
// src/Form/RapportVeterinaireType.php



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
                'label' => 'Animal',
                'required' => true,
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date du rapport',
            ])
            ->add('habitatComment', TextareaType::class, [
                'label' => 'Commentaires sur l\'habitat',
                'required' => false,
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
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer le rapport',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RapportVeterinaire::class,
        ]);
    }
}
