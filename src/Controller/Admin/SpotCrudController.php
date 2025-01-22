<?php

namespace App\Controller\Admin;

use App\Entity\Spot;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\File;

class SpotCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Spot::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom du Spot'),
            NumberField::new('latitude')
                ->setNumDecimals(15),
            NumberField::new('longitude')
                ->setNumDecimals(15),
            BooleanField::new('isPremium', 'Spot premium'),
            ImageField::new('image')
                ->setBasePath('uploads/spots_img')
                ->setUploadDir('public/uploads/spots_img')
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                ->setFormTypeOptions([
                    'constraints' => [
                        new File([
                            'mimeTypes' => [
                                'image/webp'
                            ],
                            'mimeTypesMessage' => 'Veuillez télécharger une image au format WebP uniquement.',
                        ])
                    ],
                ]),
            BooleanField::new('Marin', 'is Marin'),
        ];
    }
}
