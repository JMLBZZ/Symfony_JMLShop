<?php

namespace App\Class;

class State
{
    public const STATE = [
        '3' => [
            'label' => 'En préparation',
            'email_subject' => 'Commande en cours de préparation',
            'email_template' => 'orderState_3.html',
        ],
        '4' => [
            'label' => 'Expédiée',
            'email_subject' => 'Commande expédiée',
            'email_template' => 'orderState_4.html',
        ],
        '5' => [
            'label' => 'Annulée',
            'email_subject' => 'Commande annulée',
            'email_template' => 'orderState_5.html',
        ],
    ];
}
