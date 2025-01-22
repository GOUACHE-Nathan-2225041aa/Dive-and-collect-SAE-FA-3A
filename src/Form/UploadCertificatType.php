<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class UploadCertificatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('certificate', FileType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'le champ avatar ne peut pas être vide.']),
                    new File([
                        'mimeTypes' => [
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF valide.',
                    ])
                ],
                'label' => "Ajouter votre certificat",
                'required' => false,
                'data_class' => null,
                'row_attr' => [
                    'class' => 'flex justify-center md:my-3',
                ],
                'label_attr' => [
                    'class' => 'block text-wine text-sm font-bold cursor-pointer text-center hover:text-persian-orange hover:bg-wine shadow appearance-none border border-wine rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline',
                ],
                'attr' => [
                    'class' => 'hidden',
                    'onchange' => 'previewPicture(this)',
                    'accept' => '.pdf',
                ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'valider',
                'attr' => [
                    'class' => 'bg-fushia text-white rounded-lg py-2.5 px-5 mt-5',
                ],
                'row_attr' => [
                    'class' => 'w-full flex justify-end',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
