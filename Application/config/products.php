<?php

return [
    'field_types' => [
        'name' => 'text',
        'type' => 'select',
        'price' => 'number',
        'unit' => 'text',
        'currency' => 'text',
        'quantity' => 'number',
        'image_file_id' => 'file',
        'description' => 'textarea',
    ],

    'validation_rules' => [
        'create' => [
            'name' => 'required|string|max:255',
            'type' => 'required|in:product,service',
            'price' => 'required|numeric',
            'unit' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
            'quantity' => 'nullable|integer',
            'image_file_id' => 'nullable|integer',
            'description' => 'nullable|string',
        ],
        'update' => [
            'name' => 'required|string|max:255',
            'type' => 'required|in:product,service',
            'price' => 'required|numeric',
            'unit' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
            'quantity' => 'nullable|integer',
            'image_file_id' => 'nullable|integer',
            'description' => 'nullable|string',
        ],
    ],

    'field_mappings' => [
        'create' => [
            'name' => 'name',
            'type' => 'type',
            'price' => 'price',
            'unit' => 'unit',
            'currency' => 'currency',
            'quantity' => 'quantity',
            'image_file_id' => 'image_file_id',
            'description' => 'description',
        ],
        'update' => [
            'name' => 'name',
            'type' => 'type',
            'price' => 'price',
            'unit' => 'unit',
            'currency' => 'currency',
            'quantity' => 'quantity',
            'image_file_id' => 'image_file_id',
            'description' => 'description',
        ],
    ],

    'file_upload' => [
        'accepted_files' => 'image/jpeg,image/png,image/jpg,image/bmp',
        'max_size' => '2048',
        'path' => 'products',
    ],

    'currencies' => [
        'RON' => 'Lei (RON)',
        'EUR' => 'Euro (EUR)',
        'USD' => 'Dollar (USD)',
        'GBP' => 'Pound (GBP)',
    ],

    'form_tabs' => [
        'general' => [
            'key' => 'general',
            'label' => 'General Information',
            'title' => 'General',
            'default' => true,
        ],
        'image' => [
            'key' => 'image',
            'label' => 'Image',
            'title' => 'Product Image',
            'file_upload' => true,
        ],
        'description' => [
            'key' => 'description',
            'label' => 'Description',
            'title' => 'Product Description',
            'type' => 'textarea',
        ],
    ],

    'form_structure' => [
        'create' => [
            'tabs_enabled' => true,
            'default_tab' => 'general',
            'submit_button_position' => 'bottom-right',
            'back_button_enabled' => true,
            'back_button_icon' => 'ArrowLeft',
            'page_title' => 'Add Product',
            'form_title' => 'Add Product',
        ],
        'edit' => [
            'tabs_enabled' => true,
            'default_tab' => 'general',
            'submit_button_position' => 'bottom-right',
            'back_button_enabled' => true,
            'back_button_icon' => 'ArrowLeft',
            'page_title' => 'Edit Product',
            'form_title' => 'Edit Product',
        ],
    ],

    'form_layout' => [
        'general_tab' => [
            'rows' => [
                [
                    'fields' => ['name', 'type'],
                    'columns' => 2,
                ],
                [
                    'fields' => ['price', 'currency'],
                    'columns' => 2,
                ],
                [
                    'fields' => ['unit', 'quantity'],
                    'columns' => 2,
                    'conditional' => [
                        'quantity' => [
                            'show_when' => 'type',
                            'equals' => 'product',
                        ],
                    ],
                ],
            ],
        ],
        'description_tab' => [
            'rows' => [
                [
                    'fields' => ['description'],
                    'columns' => 1,
                    'textarea_rows' => 10,
                ],
            ],
        ],
    ],

    'form_labels' => [
        'labels' => [
            'name' => 'Name',
            'type' => 'Type',
            'price' => 'Price',
            'currency' => 'Currency',
            'unit' => 'Unit',
            'quantity' => 'Quantity',
            'description' => 'Description',
        ],
        'placeholders' => [
            'name' => 'Product name',
            'type' => 'Select type',
            'price' => '0.00',
            'currency' => 'Currency (e.g. EUR, USD, RON)',
            'unit' => 'Unit (e.g pcs, kg, m, etc.)',
            'quantity' => '0',
            'description' => 'Detailed description of the product...',
        ],
        'buttons' => [
            'create' => 'Create Product',
            'creating' => 'Creating...',
            'update' => 'Update Product',
            'updating' => 'Updating...',
            'back' => 'Back',
        ],
    ],

    'product_types' => [
        'product' => 'Product',
        'service' => 'Service',
    ],

    'breadcrumbs' => [
        'create' => [
            [
                'title' => 'Products',
                'href' => '/products',
            ],
            [
                'title' => 'Add Product',
                'href' => '/products/form',
            ],
        ],
        'edit' => [
            [
                'title' => 'Products',
                'href' => '/products',
            ],
            [
                'title' => 'Edit Product',
                'href' => '/products/edit',
            ],
        ],
    ],

    'form_fields' => [
        'create' => [
            'id' => null,
            'name' => '',
            'type' => '',
            'price' => '',
            'unit' => '',
            'currency' => '',
            'quantity' => '',
            'image_file_id' => '',
            'description' => '',
        ],
        'edit' => [
            'id' => 'product.id',
            'name' => 'product.name',
            'type' => 'product.type',
            'price' => 'product.price',
            'unit' => 'product.unit',
            'currency' => 'product.currency',
            'quantity' => 'product.quantity || ""',
            'image_file_id' => '',
            'description' => 'product.description || ""',
        ],
    ],
];
