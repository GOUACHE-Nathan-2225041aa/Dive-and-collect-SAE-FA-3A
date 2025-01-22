<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ChangeProfilPassWordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('LastPassword', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
                'label_attr' => [
                    'class'=>'font-bold'
                ],
                'attr' => [
                    'class' => 'bg-light w-full rounded-lg p-1',
                ],
                'row_attr' => [
                    'class' => 'flex flex-col mt-4',
                ],
            ])
            ->add('NewPassword', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Regex('/^(?=.?[A-Z])(?=.?[a-z])(?=.*?[0-9]).{10,}$/', 'Votre mot de passe doit contenir au minimum 10 caractères dont une majuscule, une minuscule et un chiffre'),
                ],
                'label_attr' => [
                    'class' => 'font-bold pb-1'
                ],
                'attr' => [
                    'class' => 'bg-light w-full rounded-lg p-1',
                ],
                'row_attr' => [
                    'class' => 'flex flex-col mt-4',
                ]
            ])
            ->add('ConfirmPassWord', PasswordType::class, [
                'label' => 'Confirmez le nouveau mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Regex('/^(?=.?[A-Z])(?=.?[a-z])(?=.*?[0-9]).{10,}$/', 'Votre mot de passe doit contenir au minimum 10 caractères dont une majuscule, une minuscule et un chiffre'),
                ],
                'label_attr' => [
                    'class' => 'font-bold pb-1'
                ],
                'attr' => [
                    'class' => 'bg-light w-full rounded-lg p-1',
                ],
                'row_attr' => [
                    'class' => 'flex flex-col mt-4',
                ]
            ])
            ->add('Submit', SubmitType::class, [
                'label' => 'valider',
                'attr' => [
                    'class' => ' bg-fushia text-white rounded-lg py-2.5 px-5 mt-5',
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
