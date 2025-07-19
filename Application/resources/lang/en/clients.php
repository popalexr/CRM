<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Client Management Translation Lines (English)
    |--------------------------------------------------------------------------
    |
    | These language lines are used for client management forms and messages.
    | You may modify these to match your application's terminology.
    |
    */

    'labels' => [
        'client_name' => 'Client Name',
        'client_type' => 'Client Type',
        'individual' => 'Individual',
        'business' => 'Company',
        'email' => 'Email',
        'phone' => 'Phone',
        'cui' => 'CUI',
        'registration_number' => 'Registration Number',
        'address' => 'Address',
        'city' => 'City',
        'county' => 'County',
        'country' => 'Country',
        'iban' => 'IBAN',
        'swift' => 'SWIFT/BIC',
        'bank' => 'Bank',
        'currency' => 'Currency',
        'client_tva' => 'VAT Payer',
        'notes' => 'Notes',
        'logo' => 'Client Logo',
        'contacts' => 'Client Contacts',
        'contact_name' => 'Contact Name',
        'contact_email' => 'Contact Email',
        'contact_phone' => 'Contact Phone',
        'contact_position' => 'Contact Position',
        'vat_number' => 'VAT Number',
        'tax_code' => 'Tax Code',
        'yes' => 'Yes',
        'no' => 'No',
        'general_info' => 'General Information',
        'fiscal_info' => 'Fiscal',
        'banking_info' => 'Banking',
        'optional' => 'optional',
    ],

    'placeholders' => [
        'client_name' => 'Client name',
        'select_client_type' => 'Select client type',
        'email_address' => 'Email address',
        'phone_number' => 'Phone number',
        'cui_optional' => 'CUI (if applicable)',
        'registration_number_optional' => 'Registration Number (if applicable)',
        'client_address' => 'Client address',
        'city' => 'City',
        'county' => 'County',
        'country' => 'Country',
        'iban_optional' => 'IBAN (if applicable)',
        'swift_optional' => 'SWIFT/BIC (if applicable)',
        'bank_name_optional' => 'Bank name (if applicable)',
        'currency_example' => 'Currency (e.g. EUR, USD, RON)',
        'add_notes' => 'Add notes about the client',
        'search_invoices' => 'Search invoices...',
        'search_clients' => 'Search clients...',
    ],

    'tabs' => [
        'general' => [
            'label' => 'General',
            'title' => 'General information',
        ],
        'logo' => [
            'label' => 'Logo',
            'title' => 'Client Logo',
        ],
        'contacts' => [
            'label' => 'Contacts',
            'title' => 'Client Contacts',
        ],
        'notes' => [
            'label' => 'Notes',
            'title' => 'Client Notes',
        ],
    ],

    'buttons' => [
        'add_client' => 'Add Client',
        'save_client' => 'Save Client',
        'update_client' => 'Update Client',
        'cancel' => 'Cancel',
        'back_to_clients' => 'Back to Clients',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'view' => 'View',
        'add_new' => 'Add New',
        'view_map' => 'View Map',
    ],

    'headings' => [
        'clients' => 'Clients',
        'add_client' => 'Add Client',
        'edit_client' => 'Edit Client',
        'client_details' => 'Client Details',
        'manage_clients' => 'Manage your company clients',
        'contact_persons' => 'Contact Persons',
        'invoices' => 'Invoices',
        'address' => 'Address',
        'no_clients_found' => 'No clients found',
        'get_started' => 'Get started by adding your first client.',
        'no_contact_persons' => 'No contact persons found for this client.',
        'client_type' => 'Client Type',
        'full_address' => 'Full Address',
        'vat_status' => 'VAT Status',
        'actions' => 'Actions',
        'company_name' => 'Company Name',
    ],

    'messages' => [
        'client_not_found' => 'Client not found.',
        'client_created' => 'Client created successfully.',
        'client_updated' => 'Client updated successfully.',
        'client_deleted' => 'Client deleted successfully.',
        'validation_error' => 'Please correct the errors below.',
        'permission_denied' => 'You do not have permission to access this page.',
        'no_address_available' => 'No address available',
        'no_notes_available' => 'No notes available for this client.',
        'no_invoices_found' => 'No invoices found.',
        'changes_saved_note' => 'Note: Changes here will be saved when you save the client form.',
        'vat_payer' => 'VAT Payer',
        'non_vat_payer' => 'Non-payer',
    ],

    'client_types' => [
        'individual' => 'Individual',
        'business' => 'Company',
    ],

    'status' => [
        'vat_payer' => 'VAT Payer',
        'non_vat_payer' => 'Non-payer',
    ],

];
