<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Product Management Translation Lines (Romanian)
    |--------------------------------------------------------------------------
    |
    | Aceste linii de limbă sunt folosite pentru formulare și mesaje legate de produse.
    | Poți modifica aceste valori pentru a se potrivi aplicației tale.
    |
    */

    'labels' => [
        'name' => 'Nume produs',
        'currency' => 'Monedă',
        'price' => 'Preț',
        'unit' => 'Unitate',
        'quantity' => 'Cantitate în stoc',
        'description' => 'Descriere',
        'product' => 'Produs',
        'service' => 'Serviciu',
        'commercial_details' => 'Detalii comerciale',
        'logistics_details' => 'Detalii logistice',
        'associated_invoices' => 'Facturi asociate',
        'image' => 'Imagine produs',
        'yes' => 'Da',
        'no' => 'Nu',
    ],

    'placeholders' => [
        'name' => 'Nume produs',
        'currency' => 'Monedă (ex: EUR, USD, RON)',
        'search_invoices' => 'Caută facturi...',
        'description' => 'Descriere produs',
        'stock_new_value' => 'Valoare nouă stoc',
        'stock_value' => 'Valoare',
    ],

    'tabs' => [
        'general' => [
            'label' => 'General',
            'title' => 'Informații generale',
        ],
        'image' => [
            'label' => 'Imagine',
            'title' => 'Imagine produs',
        ],
        'description' => [
            'label' => 'Descriere',
            'title' => 'Descriere produs',
        ],
        'commercial' => [
            'label' => 'Comercial',
            'title' => 'Detalii comerciale',
        ],
        'logistics' => [
            'label' => 'Logistică',
            'title' => 'Detalii logistice',
        ],
        'invoices' => [
            'label' => 'Facturi',
            'title' => 'Facturi asociate',
        ],
    ],

    'buttons' => [
        'create' => 'Creează produs',
        'creating' => 'Se creează...',
        'edit' => 'Editează produs',
        'editing' => 'Se editează...',
        'delete' => 'Șterge',
        'back' => 'Înapoi',
        'add_new' => 'Adaugă produs nou',
        'manage_stock' => 'Gestionare stoc',
        'stock_add' => 'Adaugă în stoc',
        'stock_subtract' => 'Scade din stoc',
        'stock_set' => 'Setează stoc manual',
        'cancel' => 'Anulează',
        'save' => 'Salvează',
    ],

    'headings' => [
        'products' => 'Produse și servicii',
        'add_product' => 'Adaugă produs',
        'edit_product' => 'Editează produs',
        'product_details' => 'Detalii produs',
        'manage_products' => 'Gestionează produsele și serviciile',
        'invoices' => 'Facturi asociate',
        'no_products_found' => 'Nu există produse sau servicii.',
        'get_started' => 'Începe prin a adăuga primul tău produs sau serviciu.',
        'no_invoices_found' => 'Nu au fost găsite facturi pentru acest produs.',
        'actions' => 'Acțiuni',
        'stock_add' => 'Adaugă în stoc',
        'stock_subtract' => 'Scade din stoc',
        'stock_set' => 'Setează stoc manual',
    ],

    'messages' => [
        'product_not_found' => 'Produsul nu a fost găsit.',
        'product_created' => 'Produsul a fost creat cu succes.',
        'product_updated' => 'Produsul a fost actualizat cu succes.',
        'product_deleted' => 'Produsul a fost șters cu succes.',
        'validation_error' => 'Te rugăm să corectezi erorile de mai jos.',
        'permission_denied' => 'Nu ai permisiunea să accesezi această pagină.',
        'no_description_available' => 'Nu există descriere disponibilă pentru acest produs.',
        'no_invoices_found' => 'Nu au fost găsite facturi pentru acest produs.',
        'no_notes_available' => 'Nu există note adăugate.',
        'changes_saved_note' => 'Notă: Schimbările de aici vor fi salvate când salvezi formularul produsului.',
        'invalid_stock_value' => 'Introduceți o valoare validă.',
        'stock_save_error' => 'Eroare la salvare.',
    ],

    'types' => [
        'product' => 'Produs',
        'service' => 'Serviciu',
    ],

    'status' => [
        'active' => 'Activ',
        'inactive' => 'Inactiv',
    ],
];
