<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceController extends AbstractController
{

// ##################################################################### //
// #################### FACTURE UTILISATEUR CONNECTÉ ################### //
// ##################################################################### //
    #[Route('/account/invoice/print/{id_order}', name: 'app_invoice_customer')]
    public function invoiceCustomer(OrderRepository $orderRepository, $id_order): Response
    {
        $order = $orderRepository->findOneById($id_order);

        //si order n'existe pas OU si la commande utilisteur est différent de la requete, alors retour vers la page d'accueil
        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute("app_account");
        }


        //https://github.com/dompdf/dompdf
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();

        //vue html via un fichier
        $html = $this->renderView("invoice/index.html.twig",[
            "order" => $order,
        ]);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation (landscape or portrait)
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream("CapNation.pdf",[
            "Attachment" => false,//pour ouvrir le fichier dans le navigateur
        ]);

        exit();

    }
// ##################################################################### //
// ####################### FACTURE ADMINISTRATEUR ###################### //
// ##################################################################### //
    #[Route('/admin/invoice/print/{id_order}', name: 'app_invoice_admin')]
    public function invoiceAdmin(OrderRepository $orderRepository, $id_order): Response
    {
        $order = $orderRepository->findOneById($id_order);

        //si order n'existe pas
        if(!$order){
            return $this->redirectToRoute("admin");
        }


        //https://github.com/dompdf/dompdf
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();

        //vue html via un fichier (je garde le même fichier)
        $html = $this->renderView("invoice/index.html.twig",[
            "order" => $order,
        ]);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation (landscape or portrait)
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream("CapNation.pdf",[
            "Attachment" => false,//pour ouvrir le fichier dans le navigateur
        ]);

        exit();

    }

}
