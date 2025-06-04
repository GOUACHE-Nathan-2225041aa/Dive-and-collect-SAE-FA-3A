<?php

namespace App\Form;

use App\Entity\Coordonnee;
use App\Entity\EspecePoisson;
use App\Entity\Photo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PhotoTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', FileType::class, [
                'label' => 'Image (JPEG, PNG)',
                'mapped' => false,
                'required' => true,
            ])
            ->add('espece', EntityType::class, [
                'class' => EspecePoisson::class,
                'choice_label' => 'nom', // ou autre champ lisible
            ])
            ->add('coordonnees', CoordonneeTypeForm::class, [
                'label' => 'Ajouter des coordonnées',
                'by_reference' => false,
                'required' => true,
            ]);
        // PAS de date ni d'auteur ici
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }
}
