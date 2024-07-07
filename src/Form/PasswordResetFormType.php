<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PasswordResetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                "label" => "Votre adresse email",
                "help" => "Un email de réinitialisation sera envoyé à cette adresse email.",
                "attr" => [
                    "placeholder" => "Votre adresse email"
                ],
            ])
            ->add("submit", SubmitType::class,[
                "label"=>"Réinitialiser",
                "attr" => [
                    "class" => "btn btn-dark"
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
