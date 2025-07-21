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
];
