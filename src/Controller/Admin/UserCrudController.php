<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

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
            ->setEntityLabelInSingular('Utilisateur')//nom du label "titre" dans le bouton de création
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
            ChoiceField::new("roles")
                ->setChoices([
                    "Client" => "ROLE_USER",
                    "Administrateur" => "ROLE_ADMIN",
                ])
                ->allowMultipleChoices()
                ->setLabel("Authorisations"),
            TextField::new("email")->setLabel("Email")->onlyOnIndex(),//affichage uniquement dans l'index
        ];
    }
    
    //MODIFICATION ACTIONS
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel(false);
            })
        ;
    }

}
