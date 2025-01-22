<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre nom'
                    ]),
                ],
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'class' => 'block w-full rounded border pt-6 text-placeholder mt-2 pl-2',
                    'placeholder' => 'Votre nom',
                ],
                'label_attr' => [
                    'class' => 'absolute text-black font-bold pl-2 text-outremer',
                ],
                'row_attr' => [
                    'class' => 'mb-6',
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner une adresse mail'
                    ]),
                ],
                'label' => 'Email',
                'attr' => [
                    'class' => 'block w-full rounded border pt-6 text-placeholder mt-2 pl-2',
                    'placeholder' => 'Votre email',
                ],
                'label_attr' => [
                    'class' => 'absolute text-black font-bold pl-2 text-outremer',
                ],
                'row_attr' => [
                    'class' => 'mb-6',
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Vos questions',
                'required' => true,
                'attr' => [
                    'class' => 'block w-full rounded border pt-6 text-placeholder mt-2 pl-2',
                    'placeholder' => 'Posez vos questions ici...',
                ],
                'label_attr' => [
                    'class' => 'hidden',
                ],
                'row_attr' => [
                    'class' => 'mb-6',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'w-full bg-fushia text-white font-bold p-3 rounded'
                ],
                'row_attr' => [
                    'class' => 'flex justify-center mb-6'
                ]
            ]);
    }
}
