<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    
    protected $table = 'settings';

    protected $primaryKey = 'key';
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'key',
        'type',
        'value',
    ];
}
