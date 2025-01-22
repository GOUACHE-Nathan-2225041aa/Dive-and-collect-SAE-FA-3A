<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => [
                    'class' => 'block w-full rounded border pt-6 text-placeholder mt-2 pl-2',
                    'placeholder' => "exemple@gmail.com"
                ],
                'label_attr' => [
                    'class' => 'absolute text-black font-bold pl-2 text-outremer',
                ],
                'row_attr' => [
                    'class' => 'mb-6',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'w-full bg-fushia text-white font-bold p-2 mt-4 rounded'
                ],
                'row_attr' => [
                    'class' => 'flex justify-center'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
