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
        'max_size' => '2048', // KB
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
];
