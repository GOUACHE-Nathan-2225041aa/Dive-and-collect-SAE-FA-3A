<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre prénom'
                    ]),
                ],
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'block w-full rounded border pt-6 text-placeholder mt-2 pl-2',
                    'placeholder' => 'John'],
                'label_attr' => [
                    'class' => 'absolute text-black font-bold pl-2 text-outremer',
                ],
                'row_attr' => [
                    'class' => 'mb-6',
                ]
            ])
            ->add('lastname', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre nom'
                    ]),
                ],
                'label' => 'Nom',
                'attr' => [
                    'class' => 'block w-full rounded border pt-6 text-placeholder mt-2 pl-2',
                    'placeholder' => 'Doe'],
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
                    'placeholder' => 'john.doe@gmail.com'
                ],
                'label_attr' => [
                    'class' => 'absolute text-black font-bold pl-2 text-outremer',
                ],
                'row_attr' => [
                    'class' => 'mb-6',
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{10,}$/', 'Votre mot de passe doit contenir au minimum 10 caractères dont une majuscule, une minuscule et un chiffre'),
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'class' => 'block w-full rounded border pt-6 text-placeholder mt-2 pl-2',
                        'placeholder' => '********'],
                    'label_attr' => [
                        'class' => 'absolute text-black font-bold pl-2 text-outremer',
                    ],
                    'row_attr' => [
                        'class' => 'mb-6',
                    ]
                ],
                'second_options' => ['label' => 'Confirmez le mot de passe',
                    'attr' => [
                        'class' => 'block w-full rounded border pt-6 text-placeholder mt-2 pl-2',
                        'placeholder' => '********'],
                    'label_attr' => [
                        'class' => 'absolute text-black font-bold pl-2 text-outremer',
                    ],
                    'row_attr' => [
                        'class' => 'mb-6',
                    ]
                ],
                'error_bubbling' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Inscription',
                'attr' => [
                    'class' => 'w-full bg-fushia text-white font-bold p-3 rounded'
                ],
                'row_attr' => [
                    'class' => 'flex justify-center mb-6'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
        ]);
    }
}
