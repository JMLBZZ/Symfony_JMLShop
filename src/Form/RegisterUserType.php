<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("email", EmailType::class, [
                "label"=>"Adresse mail",
                "attr" => ["placeholder" => "Votre adresse mail"],
            ])
            ->add("plainPassword", RepeatedType::class, [
                "type" => PasswordType::class,
                "constraints" => [
                    new Length([
                        "min" => 4,//au loins 4 caractères
                        "max" => 400,//au max 400 caractères
                    ]),
                ],
                "first_options"  => [
                    "label" => "Mot de passe", 
                    "attr" => [
                        "placeholder" => "Mot de passe",
                    ],
                    "hash_property_path" => "password"//hashage du pwd (via le cryptage bcrypt)
                ],
                "second_options" => [
                    "label" => "Confirmez votre mot de passe",
                    "attr" => [
                        "placeholder" => "Confirmez votre mot de passe",
                    ],
                ],
                "mapped" => false,// casse le lien entre l"entité et le champs pour le pwd
            ])
            ->add("firstname", TextType::class, [
                "label"=>"Prénom",
                "constraints" => [
                    new Length([
                        "min" => 2,//au moins 2 caractères
                        "max" => 400,//au max 400 caractères
                    ]),
                ],
                "attr" => ["placeholder" => "Votre prénom"],
            ])
            ->add("lastname", TextType::class, [
                "label"=>"Nom",
                "constraints" => [
                    new Length([
                        "min" => 2,//au loins 2 caractères
                        "max" => 400,//au max 400 caractères
                    ]),
                ],
                "attr" => ["placeholder" => "Votre nom"],
            ])
            ->add("submit", SubmitType::class,[
                "label"=>"S'inscrire",
                "attr" => ["class" => "btn btn-dark"],

                ])
            ->remove("roles")
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "constraints"=>[
                new UniqueEntity([
                    "entityClass"=>User::class,
                    "fields"=>"email"
                ])
            ],
            "data_class" => User::class,
        ]);
    }
}
