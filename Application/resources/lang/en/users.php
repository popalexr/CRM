<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Management Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for user management forms,
    | tables, messages, and other user-related content.
    |
    */

    // Form labels
    'labels' => [
        'name' => 'Name',
        'email' => 'Email Address',
        'phone' => 'Phone Number',
        'birth_date' => 'Birth Date',
        'address' => 'Address',
        'city' => 'City',
        'county' => 'County',
        'country' => 'Country',
        'password' => 'Password',
        'password_confirmation' => 'Confirm Password',
        'avatar' => 'User Avatar',
        'permissions' => 'Roles & Permissions',
        'notes' => 'Notes',
        'optional' => 'optional',
        'create_user' => 'Create User',
        'edit_user' => 'Edit User',
        'role' => 'Role',
        'administrator' => 'Administrator',
        'registered_at' => 'Registered At',
        'yes' => 'Yes',
        'no' => 'No',
    ],

    // Form placeholders
    'placeholders' => [
        'name' => 'User\'s name',
        'email' => 'email@example.com',
        'phone' => 'Phone number',
        'address' => 'User\'s address',
        'city' => 'City',
        'county' => 'County',
        'country' => 'Country',
        'password' => 'User\'s password',
        'password_confirmation' => 'Confirm password',
        'password_edit' => 'Leave blank to keep current password',
        'password_confirmation_edit' => 'Confirm new password',
        'search_invoices' => 'Search invoices...',
        'add_notes' => 'Add notes...',
    ],

    // Edit mode labels
    'edit_labels' => [
        'password' => 'New Password (optional)',
        'password_confirmation' => 'Confirm New Password',
    ],

    // Form tabs
    'tabs' => [
        'details' => [
            'label' => 'Details',
            'title' => 'User Details',
        ],
        'avatar' => [
            'label' => 'Avatar',
            'title' => 'User Avatar',
        ],
        'permissions' => [
            'label' => 'Permissions',
            'title' => 'Roles & Permissions',
        ],
        'notes' => [
            'label' => 'Notes',
            'title' => 'User Notes',
        ],
    ],

    // Buttons
    'buttons' => [
        'save' => 'Save User',
        'create' => 'Create User', 
        'update' => 'Update User',
        'add_new' => 'Add New User',
        'add_user' => 'Add User',
        'back' => 'Back',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'cancel' => 'Cancel',
        'edit_user' => 'Edit User',
    ],

    // Page titles
    'titles' => [
        'create' => 'Add User',
        'edit' => 'Edit User',
        'index' => 'Users',
    ],

    // Page headings
    'headings' => [
        'user_details' => 'User Details',
        'general_info' => 'General Information',
        'permissions' => 'Permissions',
        'address' => 'Address',
        'invoices' => 'Issued Invoices',
        'actions' => 'Actions',
    ],

    // Page descriptions
    'descriptions' => [
        'index' => 'Manage system users and their permissions',
    ],

    // Breadcrumbs
    'breadcrumbs' => [
        'users' => 'Users',
        'add_user' => 'Add User',
        'edit_user' => 'Edit User',
    ],

    // Messages
    'messages' => [
        'user_not_found' => 'User not found',
        'form_submission_error' => 'Form submission errors',
        'file_upload_error' => 'File upload error',
        'no_users_found' => 'No users found.',
        'add_first_user' => 'Add the first user',
        'user_created' => 'User created successfully',
        'user_updated' => 'User updated successfully',
        'user_deleted' => 'User deleted successfully',
        'no_address_available' => 'Address is not available',
        'no_permissions' => 'This user has no explicit permissions assigned.',
        'no_notes_available' => 'No notes available for this user.',
        'no_invoices_found' => 'No invoices found for this user.',
        'invoices_functionality' => 'The functionality to display user-issued invoices will be implemented later.',
    ],

    // Delete confirmation dialog
    'delete_dialog' => [
        'title' => 'Delete User',
        'description' => 'Are you sure you want to delete the user \':name\'? This action cannot be undone.',
        'confirm' => 'Delete',
        'cancel' => 'Cancel',
    ],

    // Table headers
    'table' => [
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'permissions' => 'Permissions',
        'created' => 'Created',
        'actions' => 'Actions',
    ],

    // Validation messages
    'validation' => [
        'required' => 'This field is required',
        'email' => 'Please enter a valid email address',
        'min_password' => 'Password must be at least 8 characters',
        'password_confirmed' => 'Password confirmation does not match',
    ],
];
