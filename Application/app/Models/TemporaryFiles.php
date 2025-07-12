<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryFiles extends Model
{
    protected $table = 'temporary_files';

    protected $fillable = [
        'file_name',
        'file_path',
        'created_at',
        'uploaded_at',
    ];

    public $timestamps = false;
}
