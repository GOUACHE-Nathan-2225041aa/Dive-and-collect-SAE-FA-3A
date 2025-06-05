<?php

namespace App\Form;

use App\Entity\Coordonnee;
use App\Entity\EspecePoisson;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EspecePoissonTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'label' => 'Name', // Label personnalisé
                'attr' => [
                    'placeholder' => 'Enter your name' // Optionnel : ajouter un placeholder
                ]
            ])
            ->add('imageFileName', FileType::class, [
                'label' => 'Species photo (JPEG or PNG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please choose a valid image (JPEG or PNG)',
                    ])
                ],
            ])
            ->add('coordonnees', CollectionType::class, [
                'entry_type' => CoordonneeTypeForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EspecePoisson::class,
        ]);
    }
}
