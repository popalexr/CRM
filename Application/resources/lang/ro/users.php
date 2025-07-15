<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Management Translation Lines (Romanian)
    |--------------------------------------------------------------------------
    |
    | These language lines are used for user management forms and messages.
    | You may modify these to match your application's terminology.
    |
    */

    // Form labels
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
        'avatar' => 'Avatar utilizator',
        'permissions' => 'Rol și permisiuni',
        'optional' => 'opțional',
        'create_user' => 'Creează utilizator',
        'edit_user' => 'Editează utilizator',
    ],

    // Form placeholders
    'placeholders' => [
        'name' => 'Numele utilizatorului',
        'email' => 'adresa@email.com',
        'phone' => 'Numărul de telefon',
        'birth_date' => 'YYYY-MM-DD',
        'address' => 'Adresa utilizatorului',
        'city' => 'Orașul',
        'county' => 'Județul',
        'country' => 'Țara',
        'password' => 'Parola utilizatorului',
        'password_confirmation' => 'Confirmă parola',
    ],

    // Tab configurations
    'tabs' => [
        'details' => [
            'label' => 'Detalii',
            'title' => 'Detalii utilizator',
        ],
        'avatar' => [
            'label' => 'Avatar',
            'title' => 'Avatar utilizator',
        ],
        'permissions' => [
            'label' => 'Permisiuni',
            'title' => 'Rol și permisiuni',
        ],
    ],

    // Button labels
    'buttons' => [
        'save' => 'Salvează utilizator',
        'create' => 'Creează utilizator',
        'update' => 'Actualizează utilizator',
        'cancel' => 'Anulează',
        'back' => 'Înapoi la utilizatori',
        'edit' => 'Editează',
        'delete' => 'Șterge',
        'add_new' => 'Adaugă utilizator nou',
    ],

    // Messages
    'messages' => [
        'user_not_found' => 'Utilizatorul nu a fost găsit',
        'user_created' => 'Utilizatorul a fost creat cu succes',
        'user_updated' => 'Utilizatorul a fost actualizat cu succes',
        'user_deleted' => 'Utilizatorul a fost șters cu succes',
        'validation_error' => 'Te rugăm să corectezi erorile de mai jos',
        'permission_denied' => 'Nu ai permisiunea să accesezi această pagină',
    ],

];