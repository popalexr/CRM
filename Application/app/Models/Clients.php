<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clients extends Model
{
    use SoftDeletes;
    
    protected $table = 'clients';
    
    protected $fillable = [
        'client_name',
        'client_logo',
        'client_type',
        'client_email',
        'client_phone',
        'cui',
        'registration_number',
        'address',
        'city',
        'county',
        'country',
        'bank_name',
        'iban',
        'swift',
        'vat_number',
        'tax_code',
        'currency',
        'notes',
    ];

    protected $dates = ['deleted_at'];
    public function contactPersons() 
    {
        return $this->hasMany(ClientContacts::class, 'client_id'); // Specificam explicit FK 'client_id'
    }
}
