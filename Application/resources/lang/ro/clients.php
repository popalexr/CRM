<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Client Management Translation Lines (Romanian)
    |--------------------------------------------------------------------------
    |
    | These language lines are used for client management forms and messages.
    | You may modify these to match your application's terminology.
    |
    */

    'labels' => [
        'client_name' => 'Nume client',
        'client_type' => 'Tip client',
        'individual' => 'Persoană fizică',
        'business' => 'Persoană juridică',
        'email' => 'Email',
        'phone' => 'Telefon',
        'cui' => 'CUI',
        'registration_number' => 'Nr. înregistrare',
        'address' => 'Adresă',
        'city' => 'Oraș',
        'county' => 'Județ',
        'country' => 'Țară',
        'iban' => 'IBAN',
        'swift' => 'SWIFT/BIC',
        'bank' => 'Bancă',
        'currency' => 'Monedă',
        'client_tva' => 'Plătitor de TVA',
        'notes' => 'Notițe',
        'logo' => 'Logo client',
        'contacts' => 'Contacte client',
        'contact_name' => 'Nume contact',
        'contact_email' => 'Email contact',
        'contact_phone' => 'Telefon contact',
        'contact_position' => 'Poziție contact',
        'vat_number' => 'CIF/Nr. TVA',
        'tax_code' => 'Cod fiscal',
        'yes' => 'Da',
        'no' => 'Nu',
        'general_info' => 'Informații generale',
        'fiscal_info' => 'Fiscal',
        'banking_info' => 'Bancar',
        'optional' => 'opțional',
    ],

    'placeholders' => [
        'client_name' => 'Numele clientului',
        'select_client_type' => 'Selectează tipul de client',
        'email_address' => 'Adresa de email',
        'phone_number' => 'Numărul de telefon',
        'cui_optional' => 'CUI (dacă se aplică)',
        'registration_number_optional' => 'Nr. înregistrare (dacă se aplică)',
        'client_address' => 'Adresa clientului',
        'city' => 'Orașul',
        'county' => 'Județul',
        'country' => 'Țara',
        'iban_optional' => 'IBAN (dacă se aplică)',
        'swift_optional' => 'SWIFT/BIC (dacă se aplică)',
        'bank_name_optional' => 'Numele băncii (dacă se aplică)',
        'currency_example' => 'Moneda (ex. EUR, USD, RON)',
        'add_notes' => 'Adaugă notițe despre client',
        'search_invoices' => 'Caută facturi...',
        'search_clients' => 'Caută clienți...',
    ],

    'tabs' => [
        'general' => [
            'label' => 'General',
            'title' => 'Informații generale',
        ],
        'logo' => [
            'label' => 'Logo',
            'title' => 'Logo client',
        ],
        'contacts' => [
            'label' => 'Contacte',
            'title' => 'Contacte client',
        ],
        'notes' => [
            'label' => 'Notițe',
            'title' => 'Notițe client',
        ],
    ],

    'buttons' => [
        'add_client' => 'Adaugă client',
        'save_client' => 'Salvează client',
        'update_client' => 'Actualizează client',
        'cancel' => 'Anulează',
        'back_to_clients' => 'Înapoi la clienți',
        'edit' => 'Editează',
        'delete' => 'Șterge',
        'view' => 'Vezi',
        'add_new' => 'Adaugă nou',
        'view_map' => 'Vezi harta',
    ],

    'headings' => [
        'clients' => 'Clienți',
        'add_client' => 'Adaugă client',
        'edit_client' => 'Editează client',
        'client_details' => 'Detalii client',
        'manage_clients' => 'Gestionează clienții companiei tale',
        'contact_persons' => 'Persoane de contact',
        'invoices' => 'Facturi',
        'address' => 'Adresă',
        'no_clients_found' => 'Nu s-au găsit clienți',
        'get_started' => 'Începe prin adăugarea primului tău client.',
        'no_contact_persons' => 'Nu s-au găsit persoane de contact pentru acest client.',
        'client_type' => 'Tip client',
        'full_address' => 'Adresa completă',
        'vat_status' => 'Status TVA',
        'actions' => 'Acțiuni',
        'company_name' => 'Nume companie',
    ],

    'messages' => [
        'client_not_found' => 'Clientul nu a fost găsit.',
        'client_created' => 'Clientul a fost creat cu succes.',
        'client_updated' => 'Clientul a fost actualizat cu succes.',
        'client_deleted' => 'Clientul a fost șters cu succes.',
        'validation_error' => 'Te rugăm să corectezi erorile de mai jos.',
        'permission_denied' => 'Nu ai permisiunea să accesezi această pagină.',
        'no_address_available' => 'Nu este disponibilă o adresă',
        'no_notes_available' => 'Nu sunt notițe disponibile pentru acest client.',
        'no_invoices_found' => 'Nu s-au găsit facturi.',
        'changes_saved_note' => 'Notă: Schimbările de aici vor fi salvate când salvezi formularul clientului.',
        'vat_payer' => 'Plătitor TVA',
        'non_vat_payer' => 'Neplătitor',
    ],

    'client_types' => [
        'individual' => 'Persoană fizică',
        'business' => 'Persoană juridică',
    ],

    'status' => [
        'vat_payer' => 'Plătitor TVA',
        'non_vat_payer' => 'Neplătitor',
    ],

];
