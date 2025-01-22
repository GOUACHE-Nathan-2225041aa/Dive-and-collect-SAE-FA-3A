<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterSpotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', SearchType::class, [
                'label' => 'Spots',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher...',
                    'class'=>'rounded-2xl my-3 bg-fushia text-white px-2 py-1',
                ],
                'label_attr' => [
                    'class' => 'w-full flex items-center justify-center text-4xl font-bold text-outremer my-4',
                ],
            ])
            ->add('checkboxes', ChoiceType::class, [
            'label' => 'Type de milieu',
            'label_attr' =>
            [
                'class'=>'text-2xl my-4 font-bold text-outremer',
            ],
            'attr'=> [
                'class'=>'p-2',
            ],
            'choices' => [
                'Marin' => 'Marin',
                'Eau douce' => 'Eau douce',
            ],
            'expanded' => true,
            'multiple' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
