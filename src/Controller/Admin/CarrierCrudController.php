<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }


    //CONFIGURATION DU CRUD
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un transporteur')//nom du label "titre" dans le bouton de création
            ->setEntityLabelInPlural('Transporteurs')// idem mais dans le corps
            // ...
        ;
    }

    // CONFIGURATION DES CHAMPS
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("name")
                ->setLabel("Nom")
                ->setHelp("(Nom du transporteur)"),
            TextareaField::new("description")
                ->setLabel("Description")
                ->setHelp("(Description du transporteur)"),
            NumberField::new("price")
                ->setLabel("Prix (TTC)")
                ->setHelp("(Prix du transporteur TTC en €)"),
        ];
    }
    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
