<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Forms Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains non-translatable form configurations for user management,
    | including field types, validation rules, and other technical settings.
    | All translatable content is now in resources/lang/users.php
    |
    */

    'field_types' => [
        'name' => 'text',
        'email' => 'email',
        'phone' => 'tel',
        'birth_date' => 'date',
        'address' => 'text',
        'city' => 'text',
        'county' => 'text',
        'country' => 'text',
        'password' => 'password',
        'password_confirmation' => 'password',
        'avatar_file_id' => 'file',
        'permissions' => 'checkbox_group',
        'notes' => 'textarea',
    ],

    'validation_rules' => [
        'create' => [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'avatar_file_id' => 'nullable|string',
            'permissions' => 'nullable|array',
            'notes' => 'nullable|string',
        ],
        'update' => [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,{id}',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'county' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar_file_id' => 'nullable|string',
            'permissions' => 'nullable|array',
            'notes' => 'nullable|string',
        ],
    ],

    'form_layout' => [
        'details_tab' => [
            'rows' => [
                [
                    'fields' => ['name', 'email'],
                    'columns' => 2,
                ],
                [
                    'fields' => ['phone', 'birth_date'],
                    'columns' => 2,
                ],
                [
                    'fields' => ['address'],
                    'columns' => 1,
                ],
                [
                    'fields' => ['city', 'county', 'country'],
                    'columns' => 3,
                ],
                [
                    'fields' => ['password', 'password_confirmation'],
                    'columns' => 2,
                ],
            ],
        ],
    ],

    'file_upload' => [
        'accepted_files' => 'image/jpeg,image/png,image/jpg,image/bmp',
        'max_size' => '2048', 
        'path' => 'avatars',
    ],

    'table_config' => [
        'per_page' => 15,
        'sortable_columns' => ['name', 'email', 'created_at'],
        'searchable_columns' => ['name', 'email', 'phone'],
    ],

    'default_permissions' => [
        'users.view',
    ],

    /*
    |--------------------------------------------------------------------------
    | User Data Field Mappings
    |--------------------------------------------------------------------------
    |
    | These mappings define how form fields map to database columns.
    | This centralized approach makes it easier to maintain and modify
    | field mappings without touching controller code.
    |
    */
    'field_mappings' => [
        'create' => [
            'name' => 'name',
            'email' => 'email',
            'phone' => 'phone',
            'birth_date' => 'birth_date',
            'address' => 'address',
            'city' => 'city',
            'county' => 'county',
            'country' => 'country',
            'permissions' => 'permissions', 
            'avatar_file_id' => 'avatar_file_id', 
            'notes' => 'notes',
        ],
        'update' => [
            'name' => 'name',
            'email' => 'email',
            'phone' => 'phone',
            'birth_date' => 'birth_date',
            'address' => 'address',
            'city' => 'city',
            'county' => 'county',
            'country' => 'country',
            'permissions' => 'permissions', 
            'avatar_file_id' => 'avatar_file_id',
            'notes' => 'notes',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Form Labels for User Details View
    |--------------------------------------------------------------------------
    |
    | These labels are used specifically in the user details view.
    | They include additional labels not covered in the main language files.
    |
    */
    'detail_labels' => [
        'labels' => [
            'role' => 'Role',
            'administrator' => 'Administrator',
            'user' => 'User',
            'registered_at' => 'Registered At',
            'general_info' => 'General Information',
            'phone' => 'Phone',
            'birth_date' => 'Birth Date',
            'yes' => 'Yes',
            'no' => 'No'
        ],
        'placeholders' => [
            'search_invoices' => 'Search invoices...'
        ],
        'headings' => [
            'user_details' => 'User Details',
            'general_info' => 'General Information',
            'permissions' => 'Permissions',
            'address' => 'Address',
            'invoices' => 'Issued Invoices',
            'actions' => 'Actions',
            'no_contact_persons' => 'No contact persons'
        ],
        'messages' => [
            'no_address_available' => 'Address is not available',
            'no_permissions' => 'This user has no explicit permissions assigned.',
            'no_notes_available' => 'No notes available for this user.',
            'no_invoices_found' => 'No invoices found for this user.',
            'invoices_functionality' => 'The functionality to display user-issued invoices will be implemented later.'
        ]
    ],
];
