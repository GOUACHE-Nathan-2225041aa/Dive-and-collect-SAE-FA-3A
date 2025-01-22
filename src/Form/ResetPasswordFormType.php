<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{10,}$/', 'Votre mot de passe doit contenir au minimum 10 caractères dont une majuscule, une minuscule et un chiffre'),
                ],
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'error_bubbling' => true,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
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
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'w-full bg-fushia text-white font-bold p-2 mt-4 rounded'
                ],
                'row_attr' => [
                    'class' => 'flex justify-center mb-6'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
