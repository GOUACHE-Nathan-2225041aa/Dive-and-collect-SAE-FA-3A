<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SendMailAdminNewSpotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('longitude', TextType::class,[
                'constraints' => [
                    new NotBlank(),
                  ],
                'label'=>'Longitude',
                'attr'=>[
                    'class'=>'bg-light w-full rounded-lg px-1',
                ],
                'row_attr'=>[
                    'class'=>'flex flex-col mt-4',
                ]
            ])
            ->add('latitude', TextType::class,[
                'constraints' => [
                    new NotBlank(),
                ],
                'label'=>'Latitude',
                'attr'=>[
                    'class'=>'bg-light w-full rounded-lg px-1',
                ],
                'row_attr'=>[
                    'class'=>'flex flex-col mt-4',
                ]
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 50,
                        'minMessage' => 'La description doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
                'label'=>'Description',
                'attr'=>[
                    'class'=>'bg-light w-full rounded-lg min-h-[150px] px-1',
                ],
                'row_attr'=>[
                    'class'=>'flex flex-col mt-4 ',
                ]
            ])
            ->add('submit',SubmitType::class, [
                'label'=>'Valider',
                'attr'=>[
                    'class'=>'bg-fushia text-white rounded-lg py-2.5 px-5 mt-5',
                ],
                'row_attr'=>
                [
                    'class'=>'w-full flex justify-end',
                ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
