<?php

namespace App\Form;

use App\Entity\EspecePoisson;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EspecePoissonTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('imageFileName', FileType::class, [
                'label' => 'Photo de l\'espèce (JPEG ou PNG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Choisis une image valide (JPEG ou PNG)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EspecePoisson::class,
        ]);
    }
}
