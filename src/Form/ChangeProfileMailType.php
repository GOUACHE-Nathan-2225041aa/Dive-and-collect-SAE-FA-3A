<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangeProfileMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("email", EmailType::class, [
                'label'=>'Adresse email actuelle',
                'constraints' => [
                    new NotBlank(),
                ],
                'attr'=>[
                    'class'=>'bg-light w-full rounded-lg p-1',
                ],
                'row_attr' => [
                    "class" =>'flex flex-col mt-4',
                ],
                'label_attr' => [
                    'class'=>'font-bold pb-1'
                ]
            ])
            ->add("Verifie", EmailType::class, [
                'label'=>'Nouvelle adresse email',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
                'attr'=>[
                    'class'=>'bg-light w-full rounded-lg p-1',
                ],
                'row_attr' => [
                    "class" =>'flex flex-col mt-4',
                ],
                'label_attr' => [
                    'class'=>'font-bold pb-1'
                ]
            ])
            ->add("submit", SubmitType::class, [
                'label'=>'valider',
                'attr'=>[
                    'class'=>'bg-fushia text-white rounded-lg py-2 px-5 mt-5',
                ],
                'row_attr' => [
                    "class" =>'w-full flex justify-end',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
