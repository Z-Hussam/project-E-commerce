<?php

namespace App\Classes;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    public function send($to_email, $to_name, $subject, $tamplate, $vars = null)
    {

        // Récuperer le contenu d'un fichier html par l'utilisation de la method file_get_contents()
        // __DIR__ variable globale permet de récupérer le répertoir jusqu'au fichier qui applle __DIR__
        // dirname() une métode permet de monter un niveau dans le répertoire 

        $content =  file_get_contents(dirname(__DIR__) . '/Mail/' . $tamplate);

        // Récupérer les variables facultatives 
        if ($vars) {
            foreach ($vars as $key => $var) {
                // str_replace est une methode permet de remplacer une valuer par l'autre 
                /* prend comme arguments 
                 1ére : ce qu'on recherche ,
                 2éme : ce qu'on veut remplacer ,
                 3éme : dans quelle variable on veut stocker le résultat */
                $content = str_replace('{' . $key . '}', $var, $content);
            }
        }

        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "dev-246@outlook.com",
                        'Name' => "Shop-online"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 6105974,
                    'TemplateLanguage' => true,
                    'Variables' => ['content' => $content],
                    'Subject' => $subject,
                    'TextPart' => "Greetings from Mailjet!",
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
    }
}
