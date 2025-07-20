<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Client Forms Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains non-translatable form configurations for client management,
    | including field types, validation rules, and field mappings.
    | All translatable content is in resources/lang/clients.php
    |
    */

    'field_types' => [
        'client_name' => 'text',
        'client_type' => 'select',
        'cui' => 'text',
        'cnp' => 'text',
        'registration_number' => 'text',
        'email' => 'email',
        'phone' => 'tel',
        'address' => 'text',
        'city' => 'text',
        'county' => 'text',
        'country' => 'text',
        'iban' => 'text',
        'swift' => 'text',
        'bank' => 'text',
        'currency' => 'select',
        'notes' => 'textarea',
        'client_tva' => 'checkbox',
        'logo_file_id' => 'file',
    ],

    'validation_rules' => [
        'create' => [
            'client_name' => 'required|string|max:255',
            'client_type' => 'required|in:individual,business',
            'cui' => 'nullable|string|max:20',
            'cnp' => 'nullable|string|max:13',
            'registration_number' => 'nullable|string|max:50',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'iban' => 'nullable|string|max:34',
            'swift' => 'nullable|string|max:11',
            'bank' => 'nullable|string|max:255',
            'currency' => 'required|string|max:3',
            'notes' => 'nullable|string',
            'client_tva' => 'boolean',
            'logo_file_id' => 'nullable|integer',
        ],
        'update' => [
            'client_name' => 'required|string|max:255',
            'client_type' => 'required|in:individual,business',
            'cui' => 'nullable|string|max:20',
            'cnp' => 'nullable|string|max:13',
            'registration_number' => 'nullable|string|max:50',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'iban' => 'nullable|string|max:34',
            'swift' => 'nullable|string|max:11',
            'bank' => 'nullable|string|max:255',
            'currency' => 'required|string|max:3',
            'notes' => 'nullable|string',
            'client_tva' => 'boolean',
            'logo_file_id' => 'nullable|integer',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Client Data Field Mappings
    |--------------------------------------------------------------------------
    |
    | These mappings define how form fields map to database columns.
    | This centralized approach makes it easier to maintain and modify
    | field mappings without touching controller code.
    |
    */
    'field_mappings' => [
        'create' => [
            'client_name' => 'client_name',
            'client_type' => 'client_type',
            'cui' => 'cui',
            'cnp' => 'cui', 
            'registrationNumber' => 'registration_number',
            'email' => 'client_email',
            'phone' => 'client_phone',
            'address' => 'address',
            'city' => 'city',
            'county' => 'county',
            'country' => 'country',
            'iban' => 'iban',
            'swift' => 'swift',
            'bank' => 'bank_name',
            'currency' => 'currency',
            'notes' => 'notes',
            'client_tva' => 'client_tva',
            'logo_file_id' => 'logo_file_id',
        ],
        'update' => [
            'client_name' => 'client_name',
            'client_type' => 'client_type',
            'cui' => 'cui',
            'cnp' => 'cui',
            'registrationNumber' => 'registration_number',
            'email' => 'client_email',
            'phone' => 'client_phone',
            'address' => 'address',
            'city' => 'city',
            'county' => 'county',
            'country' => 'country',
            'iban' => 'iban',
            'swift' => 'swift',
            'bank' => 'bank_name',
            'currency' => 'currency',
            'notes' => 'notes',
            'client_tva' => 'client_tva',
            'logo_file_id' => 'logo_file_id', 
        ],
    ],

    'file_upload' => [
        'accepted_files' => 'image/jpeg,image/png,image/jpg,image/bmp',
        'max_size' => '2048',
        'path' => 'clients',
    ],

    'table_config' => [
        'per_page' => 15,
        'sortable_columns' => ['client_name', 'client_email', 'created_at'],
        'searchable_columns' => ['client_name', 'client_email', 'client_phone'],
    ],

    'currencies' => [
        'RON' => 'Lei (RON)',
        'EUR' => 'Euro (EUR)',
        'USD' => 'Dollar (USD)',
        'GBP' => 'Pound (GBP)',
    ],
];
