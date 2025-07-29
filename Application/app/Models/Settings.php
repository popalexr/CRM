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

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return self::convert($setting, $setting->value);
    }

    private static function convert($setting, $value)
    {
        switch ($setting->type) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'float':
            case 'double':
                return (float) $value;
            case 'bool':
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'array':
                return json_encode($value);
            case 'object':
                return json_encode($value);
            default:
                return (string) $value;
        }
    }
}
