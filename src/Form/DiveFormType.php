<?php

namespace App\Form;

use App\Entity\Dive;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class DiveFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'error_bubbling' => true,
                'label' => 'Titre',
                'attr' => [
                    'class' => 'block w-full rounded border pt-6 text-placeholder mt-2 pl-2',
                    'placeholder' => $options['title_placeholder'] ?? ''
                ],
                'label_attr' => [
                    'class' => 'absolute text-black font-bold pl-2 text-outremer',
                ],
                'row_attr' => [
                    'class' => 'mb-6',
                ]
            ])
            ->add('description', TextareaType::class, [
                'error_bubbling' => true,
                'label' => 'Description',
                'attr' => [
                    'class' => 'block w-full rounded-xl border pt-6 text-placeholder mt-2 pl-2 pb-3',
                    'placeholder' => $options['description_placeholder'] ?? ''
                ],
                'label_attr' => [
                    'class' => 'absolute text-black font-bold pl-2 text-outremer bg-white rounded-tl-lg border-t border-l',
                    'style' => 'width: 99%;'
                ],
                'row_attr' => [
                    'class' => 'mb-6',
                ]
            ])
            ->add('date', DateType::class, [
                'error_bubbling' => true,
                'label' => 'Date',
                'attr' => [
                    'class' => 'block w-full rounded border pt-6 text-placeholder mt-2 pl-2',
                    'placeholder' => $options['date_placeholder'] ?? ''
                ],
                'label_attr' => [
                    'class' => 'absolute text-black font-bold pl-2 text-outremer',
                ],
                'row_attr' => [
                    'class' => 'mb-6',
                ]
            ])
            ->add('image', FileType::class, [
                'error_bubbling' => true,
                'label' => 'Importer une image',
                'attr' => [
                    'class' => 'block w-full rounded border pt-6 text-placeholder mt-2 pl-2 pb-2'
                ],
                'label_attr' => [
                    'class' => 'absolute text-black font-bold pl-2 text-outremer',
                ],
                'row_attr' => [
                    'class' => 'mb-6',
                ],
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new All([
                        new File([
                            'maxSize' => '2M',
                            'mimeTypes' => [
                                'image/webp',
                            ],
                            'mimeTypesMessage' => 'Veuillez télécharger une image au format WebP.',
                        ])
                    ])
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'valider',
                'attr' => [
                    'class' => 'bg-fushia text-white rounded-lg py-2 px-5 mt-5',
                ],
                'row_attr' => [
                    'class' => 'w-full flex justify-end',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dive::class
        ]);
    }
}
