<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'label'=>'Prénom',
                'attr' => [
                    'class' => 'bg-light rounded-lg w-full p-1',
                ],
                'label_attr' => [
                    'class'=>'font-bold pb-1'
                ],
                'row_attr' => [
                    'class' => 'flex flex-col w-5/12',
                ],

            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'label'=>'Nom',
                'attr' => [
                    'class' => 'bg-light rounded-lg w-full p-1',
                ],
                'label_attr' => [
                    'class'=>'font-bold pb-1'
                ],
                'row_attr' => [
                    'class' => 'flex flex-col w-5/12',
                ],
            ])
            ->add('profession', TextType::class, [
                'label'=>'Profession',
                'required' => false,
                'attr' => [
                    'class' => 'bg-light rounded-lg p-1',
                ],
                'label_attr' => [
                    'class'=>'font-bold pb-1'
                ],
                'row_attr' => [
                    'class' => 'flex flex-col mt-6',
                ],
            ])
            ->add('location', TextType::class, [
                'label'=>'Localisation',
                'required' => false,
                'attr' => [
                    'class' => 'bg-light rounded-lg p-1',
                ],
                'label_attr' => [
                    'class'=>'font-bold pb-1'
                ],
                'row_attr' => [
                    'class' => 'flex flex-col mt-6',
                ],
            ])
            ->add('biography', TextareaType::class, [
                'label'=>'Biographie',
                'required' => false,
                'attr' => [
                    'class' => 'bg-light rounded-lg h-32 p-1',
                ],
                'label_attr' => [
                    'class'=>'font-bold pb-1'
                ],
                'row_attr' => [
                    'class' => 'flex flex-col mt-6',
                ],

            ])
            ->add('submit', SubmitType::class, [
                'label'=>'Sauvegarder',
                'attr' => [
                    'class' => 'bg-fushia text-white rounded-lg py-2.5 px-5 mt-5 mb-10',
                ],
                'row_attr' => [
                    'class' => '',
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
