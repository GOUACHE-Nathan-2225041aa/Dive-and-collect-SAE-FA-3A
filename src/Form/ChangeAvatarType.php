<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangeAvatarType extends AbstractType
{
    // This method builds the form to change the user's avatar.
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('avatar', FileType::class, [
                'constraints'=> [
                    // Ensures that the avatar field is not empty.
                    new NotBlank(['message' => 'le champ avatar ne peut pas être vide.']),
                  
                    // Checks the maximum size and allowed file types for the avatar.
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                           // Restrict types to WebP format.
                            'image/webp',  
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier au format webp uniquement.',
                    ])
                ],
                'label' => "Modifiez votre avatar",
                'required' => false,
                'data_class' => null,
                'row_attr'=>[
                    'class'=> 'flex justify-center mt-3 mb-5',
                ],
                'label_attr'=>[
                    'class'=> 'block text-wine text-sm font-bold cursor-pointer text-center shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline',
                ],
                'attr'=>[
                    'class'=>'hidden',
               // Allows previewing the selected image.
                    'onchange' => 'previewPicture(this)',
                    'accept' => '.webp', 
                ],
               // Do not map this field to a User entity property.
                'mapped' =>false, 
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'valider',
                'attr'=> [
                    'class'=> 'bg-fushia text-white rounded-lg py-2.5 px-5 mt-5',
                ],
                'row_attr'=>[
                    'class'=>'w-full flex justify-end',
                ]
            ])
        ;
    }

    // This method configures the default options for the form.
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
