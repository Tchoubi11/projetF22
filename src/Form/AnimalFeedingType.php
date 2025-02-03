<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\AnimalFeeding;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AnimalFeedingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('feedingTime', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),  
            ])
            
            ->add('food', TextType::class, [
                'label' => 'Nourriture',
                'required' => true,
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité (en grammes)',
                'required' => true,
                'scale' => 2,  
                'attr' => ['class' => 'form-control'],
            ]);
           
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnimalFeeding::class,
        ]);
    }
}
