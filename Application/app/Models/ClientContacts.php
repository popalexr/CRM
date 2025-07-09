<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientContacts extends Model
{
    use SoftDeletes;
    
    protected $table = 'client_contacts';
    
    protected $fillable = [
        'client_id',
        'contact_name',
        'contact_email',
        'contact_phone',
        'contact_position',
    ];
}
