<?php

namespace App\Controller\Admin;

use App\Class\Mail;
use App\Class\State;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    private $em;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this-> em = $entityManagerInterface;
    }
    
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    //CONFIGURATION DU CRUD
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')//nom du label "titre" dans le bouton de création
            ->setEntityLabelInPlural('Commandes')// idem mais dans le corps
        ;
    }

    //CONFIGURATION ACTIONS
    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new("Détail")->LinkToCrudAction("show");//crée une nouvelle action et fais le lien vers la fonction
        return $actions
            // 1er param : endroit où on veux que ce soit actionné - 2eme param : quelle action on veut intéragir
            ->add(Crud::PAGE_INDEX, $show)//ajoute le bouton détail
            ->remove(Crud::PAGE_INDEX, Action::NEW)//bouton "créer"
            ->remove(Crud::PAGE_INDEX, Action::DELETE)// action "supprimer"
            ->remove(Crud::PAGE_INDEX, Action::EDIT)//action "modifier"
        ;
    }


    //changement de status de commande
    public function changeState($order, $state){

        $order->setState($state);
        $this->em->flush();

        $this->addFlash(
            'success', // type de notif (couleur danger bootstrap)
            'Status modifié !' // message de la notif
        );

        // notification email de la modif
        $mail = new Mail();
        $vars = [
            "firstname" => $order->getUser()->getFirstname(),
            "lastname" => $order->getUser()->getLastname(),
            "id_order" => $order->getUser()->getId(),
        ];
        $mail->send($order->getUser()->getEmail(), 
            $order->getUser()->getLastname()." ".$order->getUser()->getFirstname(),
            State::STATE[$state]['email_subject'], State::STATE[$state]['email_template'], $vars
        );
    }



    public function show(AdminContext $context, AdminUrlGenerator $adminUrlGenerator, Request $request){
        //Regard dans l'entité
        $order = $context->getEntity()->getInstance();

        //récupérer l'url de l'action SHOW
        $url = $adminUrlGenerator
            ->setController(self::class)
            ->setAction("show")
            ->setEntityId($order->getId())
            ->generateUrl();

        // condition du changement status
        if($request->get("state")){
            $this->changeState($order, $request->get("state"));
        }
        //Affiche la vue
        return $this->render("admin/order.html.twig",[
            "order" => $order,
            "current_url" => $url
        ]);
    }

    // CONFIGURATION DES CHAMPS
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt')->setLabel("Date"),
            NumberField::new('state')->setLabel("Statut")->setTemplatePath("admin/state.html.twig"),
            AssociationField::new('user')->setLabel("Utilisateur"),
            TextField::new('carrierName')->setLabel("Transporteur"),
            NumberField::new('totalTva')->setLabel("Total TVA"),
            NumberField::new('totalPriceTva')->setLabel("Total TTC")
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
