<?php

namespace App\Controller\Admin;

use App\Entity\Oceanarium;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OceanariumCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Oceanarium::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            NumberField::new('species_id'),
        ];
    }

}
