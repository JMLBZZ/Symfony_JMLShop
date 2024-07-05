<?php

namespace App\Class;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    public function send($to_email, $to_name, $subject, $template, $vars = null)
    {

        //récupération du template
        $content = file_get_contents(dirname(__DIR__)."/Mail/".$template);
        // récupération des variables présente ou non
        if ($vars){
            foreach($vars as $key=>$var){
                $content = str_replace("{".$key."}", $var, $content);//3 paramètres : recherche, remplace, la variable à remplacer
            }
        }

        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "jamel.bouazza@hotmail.fr",
                        'Name' => "CAP NATION"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 6113973,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        "content" => $content,
                    ],
                ]
            ]
        ];

        $mj->post(Resources::$Email, ['body' => $body]);
    }
}
