<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Product Management Translation Lines (English)
    |--------------------------------------------------------------------------
    |
    | These language lines are used for product management forms and messages.
    | You may modify these to match your application's terminology.
    |
    */

    'labels' => [
        'name' => 'Product name',
        'currency' => 'Currency',
        'price' => 'Price',
        'unit' => 'Unit',
        'quantity' => 'Quantity in Stock',
        'description' => 'Description',
        'product' => 'Product',
        'service' => 'Service',
        'commercial_details' => 'Commercial Details',
        'logistics_details' => 'Logistics Details',
        'associated_invoices' => 'Associated Invoices',
        'image' => 'Product Image',
        'yes' => 'Yes',
        'no' => 'No',
    ],

    'placeholders' => [
        'name' => 'Product name',
        'currency' => 'Currency (e.g. EUR, USD, RON)',
        'search_invoices' => 'Search invoices...',
        'description' => 'Product description',
    ],

    'tabs' => [
        'general' => [
            'label' => 'General',
            'title' => 'General Information',
        ],
        'image' => [
            'label' => 'Image',
            'title' => 'Product Image',
        ],
        'description' => [
            'label' => 'Description',
            'title' => 'Product Description',
        ],
        'commercial' => [
            'label' => 'Commercial',
            'title' => 'Commercial Details',
        ],
        'logistics' => [
            'label' => 'Logistics',
            'title' => 'Logistics Details',
        ],
        'invoices' => [
            'label' => 'Invoices',
            'title' => 'Associated Invoices',
        ],
    ],

    'buttons' => [
        'create' => 'Create Product',
        'creating' => 'Creating...',
        'edit' => 'Edit Product',
        'editing' => 'Editing...',
        'delete' => 'Delete',
        'back' => 'Back',
        'add_new' => 'Add New Product',
    ],

    'headings' => [
        'products' => 'Products and Services',
        'add_product' => 'Add Product',
        'edit_product' => 'Edit Product',
        'product_details' => 'Product Details',
        'manage_products' => 'Manage products and services',
        'invoices' => 'Associated Invoices',
        'no_products_found' => 'There are no products or services.',
        'get_started' => 'Start by adding your first product or service.',
        'no_invoices_found' => 'No invoices found for this product.',
        'actions' => 'Actions',
    ],

    'messages' => [
        'product_not_found' => 'Product not found.',
        'product_created' => 'Product created successfully.',
        'product_updated' => 'Product updated successfully.',
        'product_deleted' => 'Product deleted successfully.',
        'validation_error' => 'Please correct the errors below.',
        'permission_denied' => 'You do not have permission to access this page.',
        'no_description_available' => 'No description available for this product.',
        'no_invoices_found' => 'No invoices found for this product.',
        'no_notes_available' => 'No notes available.',
        'changes_saved_note' => 'Note: Changes here will be saved when you save the product form.',
    ],

    'types' => [
        'product' => 'Product',
        'service' => 'Service',
    ],

    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ],
];
