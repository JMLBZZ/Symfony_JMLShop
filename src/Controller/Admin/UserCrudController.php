<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    //CONFIGURATION DU CRUD
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un utilisateur')//nom du label "titre" dans le bouton de création
            ->setEntityLabelInPlural('Utilisateurs')// idem mais dans le corps
            // ...
        ;
    }

    
    // CONFIGURATION DES CHAMPS
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("lastname")->setLabel("Nom"),
            TextField::new("firstname")->setLabel("Prénom"),
            TextField::new("email")->setLabel("Email")->onlyOnIndex(),//affichage uniquement dans l'index
        ];
    }


}
