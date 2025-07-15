<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Permissions Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all available permissions for the CRM system.
    | Permissions are organized by category for better management.
    |
    */

    'Users' => [
        'users.view'    => 'View Users',
        'users.create'  => 'Create Users',
        'users.edit'    => 'Edit Users',
        'users.delete'  => 'Delete Users',
    ],

    'Clients' => [
        'clients.view'    => 'View Clients',
        'clients.create'  => 'Create Clients',
        'clients.edit'    => 'Edit Clients',
        'clients.delete'  => 'Delete Clients',
    ],

    'Settings' => [
        'settings.view'   => 'View Settings',
        'settings.edit'   => 'Edit Settings',
    ],

    'Reports' => [
        'reports.view'    => 'View Reports',
        'reports.create'  => 'Create Reports',
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Categories
    |--------------------------------------------------------------------------
    |
    | Human-readable names for permission categories.
    |
    */

    'categories' => [
        'Users' => 'User Management',
        'Clients' => 'Client Management',
        'Settings' => 'System Settings',
        'Reports' => 'Reports & Analytics',
    ],
];