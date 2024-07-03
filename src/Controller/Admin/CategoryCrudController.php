<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    //CONFIGURATION DU CRUD
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('une catégorie')//nom du label "titre" dans le bouton de création
            ->setEntityLabelInPlural('Catégories')// idem mais dans le corps
            // ...
        ;
    }

    // CONFIGURATION DES CHAMPS
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("name")
                ->setLabel("Nom")
                ->setHelp("(Nom de la catégorie)"),
            SlugField::new("slug")
                ->setLabel("URL")
                ->setTargetFieldName("name")//copie le champs "nom" pour le slug
                ->setHelp("(URL généré automatiquement)"),
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
