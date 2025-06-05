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
        if (!array_key_exists('is_edit', $options) || !$options['is_edit']) {
            // Formulaire de création : on a besoin du champ image
            $builder->add('imageFile', FileType::class, [
                'label' => 'Image (JPEG, PNG)',
                'mapped' => false,
                'required' => true,
            ]);
        }
        $builder
            ->add('espece', EntityType::class, [
                'class' => EspecePoisson::class,
                'choice_label' => 'nom', // ou autre champ lisible
            ])
            ->add('coordonnees', CoordonneeTypeForm::class, [
                'label' => 'Add Coordinates',
                'by_reference' => false,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
            'is_edit' => false, // Option personnalisée, avec une valeur par défaut
        ]);
    }
}
