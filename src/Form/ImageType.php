<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('imageFile', FileType::class, [
            'label' => 'Télécharger une image',
            'mapped' => false, // Ce champ n'est pas directement mappé à une propriété de l'entité
            'required' => true,
            'constraints' => [
            new File([
                'maxSize' => '40M', 
                'mimeTypes' => [
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                ],
                'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, GIF).',
            ]),
        ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'data_class' => Image::class,  
        ]);
    }
}
