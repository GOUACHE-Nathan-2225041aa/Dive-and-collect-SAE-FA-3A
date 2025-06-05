<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\Photo;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'label' => 'Title',
                'attr' => [
                    'placeholder' => 'Enter the mission title'
                ]
            ])
            ->add('descriptionCourte', null, [
                'label' => 'Short description',
                'attr' => [
                    'placeholder' => 'Brief summary (max 255 characters)'
                ]
            ])
            ->add('description', null, [
                'label' => 'Full description',
                'attr' => [
                    'placeholder' => 'Detailed description of the mission'
                ]
            ])
            ->add('dateDebut', null, [
                'label' => 'Start date',
                'attr' => [
                    'placeholder' => 'Select start date'
                ],
                'widget' => 'single_text' // Pour un sélecteur de date moderne
            ])
            ->add('dateFin', null, [
                'label' => 'End date',
                'attr' => [
                    'placeholder' => 'Select end date'
                ],
                'widget' => 'single_text'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
