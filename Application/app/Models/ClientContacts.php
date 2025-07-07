<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientContacts extends Model
{
    protected $table = 'client_contacts';
    
    protected $fillable = [
        'client_id',
        'name',
        'email',
        'phone',
    ];
    
    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }
}
