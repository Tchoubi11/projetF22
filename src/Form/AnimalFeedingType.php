<?php

// src/Form/AnimalFeedingType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\AnimalFeeding;

class AnimalFeedingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('feedingTime', DateTimeType::class, [
            'widget' => 'single_text',
            'required' => false,  // Champ optionnel
            'data' => $feedingTime ?? new \DateTime(), // Valeur par défaut si feedingTime est null
        ])
            ->add('nourriture', TextType::class, [
                'label' => 'Nourriture',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnimalFeeding::class,
        ]);
    }
}
