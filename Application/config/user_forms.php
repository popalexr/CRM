<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Forms Constants
    |--------------------------------------------------------------------------
    |
    | This file contains all form-related constants for user management,
    | including field labels, placeholders, messages, and other form constants.
    |
    */

    'labels' => [
        'name' => 'Nume',
        'email' => 'Adresă de email',
        'phone' => 'Număr telefon',
        'birth_date' => 'Data nașterii',
        'address' => 'Adresă',
        'city' => 'Oraș',
        'county' => 'Județ',
        'country' => 'Țară',
        'password' => 'Parolă',
        'password_confirmation' => 'Confirmă parola',
        'avatar' => 'Avatar Utilizator',
        'permissions' => 'Rol și Permisiuni',
    ],

    'placeholders' => [
        'name' => 'Numele utilizatorului',
        'email' => 'email@example.com',
        'phone' => 'Numărul de telefon',
        'address' => 'Adresa utilizatorului',
        'city' => 'Orașul',
        'county' => 'Județul',
        'country' => 'Țara',
        'password' => 'Parola utilizatorului',
        'password_confirmation' => 'Confirmă parola',
        'password_edit' => 'Lasă gol pentru a păstra parola actuală',
        'password_confirmation_edit' => 'Confirmă parola nouă',
    ],

    'edit_labels' => [
        'password' => 'Parolă nouă (opțional)',
        'password_confirmation' => 'Confirmă parola nouă',
    ],

    'tabs' => [
        'details' => [
            'key' => 'details',
            'label' => 'Detalii User',
            'title' => 'Detalii User',
        ],
        'avatar' => [
            'key' => 'avatar', 
            'label' => 'Avatar',
            'title' => 'Avatar Utilizator',
        ],
        'permissions' => [
            'key' => 'permissions',
            'label' => 'Rol și Permisiuni', 
            'title' => 'Rol și Permisiuni',
        ],
    ],

    'buttons' => [
        'create' => 'Salvează Utilizator',
        'update' => 'Actualizează Utilizator',
        'add_user' => 'Add User',
        'back' => 'Înapoi',
    ],

    'titles' => [
        'create' => 'Add User',
        'edit' => 'Edit User',
        'index' => 'Users',
    ],

    'descriptions' => [
        'index' => 'Manage system users and their permissions',
    ],

    'breadcrumbs' => [
        'users' => 'Users',
        'add_user' => 'Add User', 
        'edit_user' => 'Edit User',
    ],

    'messages' => [
        'user_not_found' => 'User not found',
        'form_submission_error' => 'Form submission errors',
        'file_upload_error' => 'File upload error',
        'no_users_found' => 'No users found.',
        'add_first_user' => 'Add the first user',
    ],

    'table_headers' => [
        'name' => 'Name',
        'email' => 'Email', 
        'phone' => 'Phone',
        'permissions' => 'Permissions',
        'created' => 'Created',
        'actions' => 'Actions',
    ],

    'actions' => [
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],

    'file_upload' => [
        'accepted_files' => 'image/jpeg,image/png,image/jpg,image/bmp',
    ],

    'validation_messages' => [
        'required' => 'This field is required',
        'email' => 'Please enter a valid email address',
        'min_password' => 'Password must be at least 8 characters',
        'password_confirmed' => 'Password confirmation does not match',
    ],
];
