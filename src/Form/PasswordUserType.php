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
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class, [
                "label"=>"Votre mot de passe actuel",
                "attr" => [
                    "placeholder" => "Votre mot de passe actuel",
                ],
                "mapped" => false,
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
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()["data"];
                $passwordHasher = $form->getConfig()->getOptions()["passwordHasher"];
                
                //récupère le pwd saisie et compare avec celui de la bdd :
                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get("actualPassword")->getData()
                );

                //dd($isValid);

                // condition pour les erreurs
                if (!$isValid){
                    $form->get("actualPassword")->addError(new FormError("Votre mot de passe n'est pas bonne. veuillez resaisir le bon mot de passe."));
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            "passwordHasher" => null
        ]);
    }
}
