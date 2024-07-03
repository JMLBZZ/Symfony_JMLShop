<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                "label"=>"Prénom",
                "attr" => ["placeholder" => "Votre prénom"],
            ])
            ->add('lastname', TextType::class, [
                "label"=>"Nom",
                "attr" => ["placeholder" => "Votre nom"],
            ])
            ->add('address', TextType::class, [
                "label"=>"Adresse",
                "attr" => ["placeholder" => "Votre adresse"],
            ])
            ->add('postal', TextType::class, [
                "label"=>"Code postal",
                "attr" => ["placeholder" => "Votre code postal"],
            ])
            ->add('city', TextType::class, [
                "label"=>"Ville",
                "attr" => ["placeholder" => "Votre ville"],
            ])
            ->add('country', CountryType::class, [
                "label" => "Pays", 
                "data" => "FR"
            ])
            ->add('phone', TextType::class, [
                "label"=>"Téléphone",
                "attr" => ["placeholder" => "Votre numéro de téléphone"],
            ])
            ->add("submit", SubmitType::class,[
                "label"=>"Ajouter",
                "attr" => ["class" => "btn btn-dark"],
            ])
            ->remove('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
