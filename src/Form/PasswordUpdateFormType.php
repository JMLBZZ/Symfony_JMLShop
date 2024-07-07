<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordUpdateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("plainPassword", RepeatedType::class, [
                "type" => PasswordType::class,
                "constraints" => [
                    new Length([
                        "min" => 4,//au loins 4 caractères
                        "max" => 400,//au max 400 caractères
                    ]),
                ],
                "first_options"  => [
                    "label" => "Nouveau mot de passe", 
                    "attr" => [
                        "placeholder" => "Nouveau mot de passe",
                    ],
                    "hash_property_path" => "password"//hashage du pwd (via le cryptage bcrypt)
                ],
                "second_options" => [
                    "label" => "Confirmez votre nouveau mot de passe",
                    "attr" => [
                        "placeholder" => "Confirmez votre nouveau mot de passe",
                    ],
                ],
                "mapped" => false,// casse le lien entre l"entité et le champs pour le pwd
            ])
            ->add("submit", SubmitType::class,[
                "label"=>"Modifier",
                "attr" => ["class" => "btn btn-dark"],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
