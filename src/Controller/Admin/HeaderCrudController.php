<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }
    //CONFIGURATION DU CRUD
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Bannière')//nom du label "titre" dans le bouton de création
            ->setEntityLabelInPlural('Bannière')// idem mais dans le corps
            // ...
        ;
    }
    // CONFIGURATION DES CHAMPS
    public function configureFields(string $pageName): iterable
    {
        $required=true;
        if ($pageName=="edit"){
            $required=false;
        }

        return [
            TextField::new('title', "Titre"),
            TextareaField::new('content', "Contenu"),
            TextField::new('buttonTitle', "Titre du bouton"),
            TextField::new('buttonLink', "Url du bouton"),
            ImageField::new("image")
                ->setLabel("Image")
                ->setHelp("Image arrière plan de la bannière promotionnelle")
                ->setUploadDir("/public/uploads")//enregistre l'image dans le dossier "uploads"
                ->setUploadedFileNamePattern("[year][month][day]-[timestamp]-[contenthash].[extension]")//rename le fichier uploadé
                ->setBasePath("/uploads")//affiche l'image dasn le chemin
                ->setRequired($required),
        ];
    }
    
}
