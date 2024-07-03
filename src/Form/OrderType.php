<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addresses', EntityType::class, [
                "class"=>Address::class,// on défninie quelle entité elle est lié (ici Address)
                "label"=>"Adresse :",
                "attr" => ["placeholder" => "Choisissez votre adresse de livraison"],
                "required" => true,
                "expanded" => true,
                "choices" => $options["addresses"],
                "label_html" => true,//pour interpréter les balise html dans le formulaire (voir orderController)
            ])
            ->add('carriers', EntityType::class, [
                "class"=>Carrier::class,// on défninie quelle entité elle est lié (ici Carrier)
                "label"=>"Transporteur :",
                "attr" => ["placeholder" => "Choisissez votre transporteur de livraison"],
                "required" => true,
                "expanded" => true,
                "label_html" => true,//pour interpréter les balise html dans le formulaire (voir orderController)
            ])
            ->add("submit", SubmitType::class,[
                "label"=>"Valider",
                "attr" => ["class" => "btn btn-dark"],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "addresses" => null,
        ]);
    }
}
