<?php

namespace App\Form;

use App\Entity\RapportVeterinaire;
use App\Entity\Animal;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RapportVeterinaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('animal', EntityType::class, [
                'class' => Animal::class,
                'choice_label' => 'prenom', 
                'label' => 'Animal',
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date du rapport',
            ])
            ->add('habitatComment', TextareaType::class, [
                'label' => 'Commentaires sur l\'habitat',
                'required' => false,
            ])
            ->add('feedings', CollectionType::class, [
                'entry_type' => TextType::class, // ou vous pouvez créer un type de formulaire spécifique pour l'alimentation
                'entry_options' => ['label' => 'Nourriture'],
                'allow_add' => true, // permet d'ajouter plusieurs alimentations
                'allow_delete' => true, // permet de supprimer des alimentations
                'by_reference' => false,
            ])
            ->add('observations', TextareaType::class, [
                'label' => 'Observations',
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
