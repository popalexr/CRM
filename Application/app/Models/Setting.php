<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    
    protected $table = 'settings';
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'key',
        'type',
        'value',
    ];

    /**
     * Get available company types for Romanian companies.
     *
     * @return array
     */
    public static function getCompanyTypes(): array
    {
        return config('application_settings_config.company_types', []);
    }

    /**
     * Get default VAT rates for Romanian companies.
     *
     * @return array
     */
    public static function getDefaultVatRates(): array
    {
        return [
            ['id' => 'standard', 'rate' => 19.0, 'name' => 'TVA Standard (19%)'],
            ['id' => 'reduced', 'rate' => 9.0, 'name' => 'TVA Redus (9%)'],
            ['id' => 'zero', 'rate' => 0.0, 'name' => 'TVA 0%'],
        ];
    }

    /**
     * Get form labels and placeholders for the settings form.
     *
     * @return array
     */
    public static function getFormLabels(): array
    {
        return config('application_settings_config.form_labels', []);
    }
}
