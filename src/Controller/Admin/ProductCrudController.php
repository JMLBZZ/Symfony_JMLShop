<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    //CONFIGURATION DU CRUD
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un produit')//nom du label "titre" dans le bouton de création
            ->setEntityLabelInPlural('Produits')// idem mais dans le corps
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
            //IdField::new('id'),
            TextField::new('name')
                ->setLabel("Nom")
                ->setHelp("(Nom du produit)"),
            SlugField::new('slug')->setTargetFieldName("name")
                ->setLabel("URL")
                ->setTargetFieldName("name")//copie le champs "nom" pour le slug
                ->setHelp("(URL généré automatiquement)"),
            TextEditorField::new('description')
                ->setLabel("Description")
                ->setHelp("(Description du produit)"),
            ImageField::new("image")
                ->setLabel("Image")
                ->setHelp("")
                ->setUploadDir("/public/uploads")//enregistre l'image dans le dossier "uploads"
                ->setUploadedFileNamePattern("[year][month][day]-[timestamp]-[contenthash].[extension]")//rename le fichier uploadé
                ->setBasePath("/uploads")//affiche l'image dasn le chemin
                ->setRequired($required),

            NumberField::new("price")
                ->setLabel("Prix (HT)")
                ->setHelp("(Prix du produit Hors Taxe en €)"),
            ChoiceField::new("tva")
                ->setLabel("TVA (%)")
                ->setHelp("(Taux de la TVA)")
                ->setChoices([
                    "5,5 %"=>"5.5",//première valeur est l'écriture, deuxième valeur et le stockage en bdd
                    "10 %"=>"10",
                    "20 %"=>"20",
                ]),
            AssociationField::new("category","Catégorie"),
        ];
    }
    
}
