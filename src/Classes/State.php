<?php

namespace App\Classes;


class State
{
    public const STATE = [
        '3' => [
            'label' => 'En cours de préparation',
            'email_subject' => 'Commande en cours de préparation',
            'email_tamplate' => 'order_state_3.html'
        ],
        '4' => [
            'label' => 'Expédiée',
            'email_subject' => 'Commande expédiée',
            'email_tamplate' => 'order_state_4.html'
        ],
        '5' => [
            'label' => 'Annullée',
            'email_subject' => 'Commande annullée',
            'email_tamplate' => 'order_state_5.html'
        ],
        '6'=>[
            'label'=> 'Livré',
            'email_subject' => 'Commande Livrée',
            'email_tamplate' => 'order_state_6.html'
        ]
    ];
}
