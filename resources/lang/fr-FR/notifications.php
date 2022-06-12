<?php

return [

    'whoops'              => 'Oups !',
    'hello'               => 'Bonjour !',
    'salutation'          => 'Cordialement,<br>:company_name',
    'subcopy'             => 'Si vous n\'arrivez pas à cliquer sur le bouton ":text", veuillez copier et coller l\'URL ci-dessous dans votre navigateur web : [:url](:url)',
    'mark_read'           => 'Marquer comme lu',
    'mark_read_all'       => 'Tout marquer comme lu',
    'empty'               => 'Woohoo, aucune notification!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Échec de la mise à jour sur :domain',
            'description'   => 'La mise à jour de :alias de :current_version à :new_version a échoué à l\'étape <strong>:step</strong> avec le message suivant : :error_message',

        ],

        'slack' => [

            'description'   => 'La mise à jour a échoué sur :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Importation terminée',
            'description'   => 'L\'importation est terminée et les enregistrements sont disponibles dans votre panel.',

        ],

        'failed' => [

            'title'         => 'Importation échouée',
            'description'   => 'Impossible d\'importer le fichier en raison des problèmes suivants :',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'L\'export est prêt',
            'description'   => 'Le fichier d\'exportation est prêt à être téléchargé à partir du lien suivant :',

        ],

        'failed' => [

            'title'         => 'L\'exportation a échoué',
            'description'   => 'Impossible de créer le fichier d\'export en raison du problème suivant :',

        ],

    ],

    'menu' => [

        'export_completed' => [

            'title'         => 'L\'export est prêt',
            'description'   => 'Votre fichier d\'exportation <strong>:type</strong> est prêt à être<a href=":url" target="_blank"><strong>téléchargé</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'L\'exportation a échoué',
            'description'   => 'Impossible de créer le fichier d\'export en raison du problème suivant : :issues',

        ],

        'import_completed' => [

            'title'         => 'Importation terminée',
            'description'   => 'Vos données <strong>:type</strong> lignées <strong>:count</strong> ont été importées avec succès.',

        ],

        'new_apps' => [

            'title'         => 'Nouvelle application',
            'description'   => 'L\'application <strong>:name</strong> est sortie. Vous pouvez <a href=":url">cliquer ici</a> pour voir les détails.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Nouvelle facture',
            'description'   => ' La facture<strong>:invoice_number</strong> est créée. Vous pouvez <a href=":invoice_portal_link">cliquer ici</a> pour voir les détails et procéder au paiement.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Facture en retard',
            'description'   => 'Lla facture <strong>:invoice_number</strong> était due au <strong>:invoice_due_date</strong>. Vous pouvez <a href=":invoice_portal_link">cliquer ici</a> pour voir les détails et procéder au paiement.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Facture en retard',
            'description'   => 'La facture<strong>:invoice_number</strong> était due au <strong>:invoice_due_date</strong>. Vous pouvez <a href=":invoice_admin_link">cliquer ici</a> pour voir les détails.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Nouvelle facture récurrente',
            'description'   => 'La facture <strong>:invoice_number</strong> est créée en fonction de votre cycle récurrent. Vous pouvez <a href=":invoice_portal_link">cliquer ici</a> pour voir les détails et procéder au paiement.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Nouvelle facture récurrente',
            'description'   => 'La facture <strong>:invoice_number</strong> est créée pour <strong>:customer_name</strong> sur la base du cycle récurrent. Vous pouvez <a href=":invoice_admin_link">cliquer ici</a> pour voir les détails.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Facture consultée',
            'description'   => '<strong>:customer_name</strong> a consulté la facture <strong>:invoice_number</strong> . Vous pouvez <a href=":invoice_admin_link">cliquer ici</a> pour voir les détails.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Paiement reçu',
            'description'   => 'Merci pour le paiement de la facture <strong>:invoice_number</strong> . Vous pouvez <a href=":invoice_portal_link">cliquer ici</a> pour voir les détails.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Paiement reçu',
            'description'   => 'Merci pour le paiement de la facture <strong>:invoice_number</strong> . Vous pouvez <a href=":invoice_portal_link">cliquer ici</a> pour voir les détails.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Paiement reçu',
            'description'   => ':customer_name a enregistré le paiement pour la facture <strong>:invoice_number</strong> . Vous pouvez <a href=":invoice_admin_link">cliquer ici</a> pour voir les détails.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Facture en retard',
            'description'   => 'La facture <strong>:bill_number</strong> était due au <strong>:bill_due_date</strong>. Vous pouvez <a href=":bill_admin_link">cliquer ici</a> pour voir les détails.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Nouvelle facture récurrente',
            'description'   => 'La facture <strong>:bill_number</strong> est créée par <strong>:vendor_name</strong> sur la base du cylce récurrent. Vous pouvez <a href=":bill_admin_link">cliquer ici</a> pour voir les détails.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type est en lecture de cette notification !',
        'mark_read_all'         => ':type est en train de lire toutes les notifications !',

    ],
];
