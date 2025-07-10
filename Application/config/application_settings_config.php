<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration values specific to this CRM application,
    | including company types, form labels, and other application-specific settings.
    |
    */

    'company_types' => [
        ['value' => 'srl', 'label' => 'SRL'],
        ['value' => 'sa', 'label' => 'SA'],
        ['value' => 'pfa', 'label' => 'PFA'],
        ['value' => 'ii', 'label' => 'Întreprindere Individuală'],
        ['value' => 'ong', 'label' => 'ONG'],
        ['value' => 'alte', 'label' => 'Altele'],
    ],

    'form_labels' => [
        'placeholders' => [
            'company_name' => 'Numele companiei',
            'company_type' => 'Selectați tipul companiei',
            'address' => 'Adresa completă',
            'city' => 'Orașul',
            'county' => 'Județul',
            'country' => 'Țara',
            'email' => 'Email companiei',
            'phone' => 'Telefon companiei',
            'iban' => 'Codul IBAN',
            'bank' => 'Numele băncii',
            'swift' => 'Codul SWIFT/BIC',
            'vat_name' => 'Numele cotei (ex: TVA Standard)',
            'vat_rate' => 'Procentul (ex: 19)',
        ],
        'labels' => [
            'company_name' => 'Numele Companiei',
            'company_type' => 'Tipul Companiei',
            'address' => 'Adresa',
            'city' => 'Orașul',
            'county' => 'Județul',
            'country' => 'Țara',
            'email' => 'Email',
            'phone' => 'Telefon',
            'iban' => 'IBAN',
            'bank' => 'Banca',
            'swift' => 'SWIFT/BIC',
            'vat_payer' => 'Plătitor de TVA',
            'vat_name' => 'Numele Cotei',
            'vat_rate' => 'Procentul (%)',
        ],
        'buttons' => [
            'save_company' => 'Salvează Informațiile Companiei',
            'add_vat' => 'Adaugă Cota TVA',
            'delete_vat' => 'Șterge',
        ],
        'headings' => [
            'page_title' => 'Setări Aplicație',
            'breadcrumb_settings' => 'Setări',
            'company_info' => 'Informații Companie',
            'company_info_desc' => 'Configurați informațiile generale ale companiei dumneavoastră.',
            'vat_rates' => 'Cote TVA',
            'vat_rates_desc' => 'Gestionați cotele de TVA utilizate în aplicație.',
            'vat_configured' => 'Cote TVA Configurate',
            'add_new_vat' => 'Adaugă Cotă TVA Nouă',
        ],
        'messages' => [
            'delete_vat_confirm' => 'Sunteți sigur că doriți să ștergeți această cotă TVA?',
        ],
    ],

];
