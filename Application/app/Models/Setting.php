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
        return [
            ['value' => 'PFA', 'label' => 'PFA (Persoană Fizică Autorizată)'],
            ['value' => 'II', 'label' => 'II (Întreprindere Individuală)'],
            ['value' => 'SRL', 'label' => 'SRL (Societate cu Răspundere Limitată)'],
            ['value' => 'SA', 'label' => 'SA (Societate pe Acțiuni)'],
            ['value' => 'SNC', 'label' => 'SNC (Societate în Nume Colectiv)'],
            ['value' => 'SCS', 'label' => 'SCS (Societate în Comandită Simplă)'],
        ];
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
        return [
            'placeholders' => [
                'company_name' => 'Ex: SC Example SRL',
                'company_type' => 'Selectează tipul companiei',
                'address' => 'Strada, numărul',
                'city' => 'Ex: București',
                'county' => 'Ex: București',
                'country' => 'Ex: România',
                'email' => 'contact@company.ro',
                'phone' => '+40 xxx xxx xxx',
                'iban' => 'RO49 AAAA 1B31 0075 9384 0000',
                'bank' => 'Ex: BCR, BRD, ING',
                'swift' => 'Ex: RNCBROBU',
                'vat_name' => 'Ex: TVA Standard',
                'vat_rate' => 'Ex: 19'
            ],
            'labels' => [
                'company_name' => 'Nume Companie',
                'company_type' => 'Tip Companie',
                'address' => 'Adresa',
                'city' => 'Oraș',
                'county' => 'Județ',
                'country' => 'Țara',
                'email' => 'Email',
                'phone' => 'Telefon',
                'iban' => 'IBAN',
                'bank' => 'Banca',
                'swift' => 'Cod SWIFT',
                'vat_payer' => 'Plătitor TVA',
                'vat_name' => 'Denumire',
                'vat_rate' => 'Cotă (%)'
            ],
            'buttons' => [
                'save_company' => 'Salvează Informațiile Companiei',
                'add_vat' => 'Adaugă',
                'delete_vat' => 'Șterge'
            ],
            'headings' => [
                'page_title' => 'Setări Aplicație',
                'company_info' => 'Informații Generale Companie',
                'company_info_desc' => 'Configurează informațiile de bază ale companiei tale',
                'vat_rates' => 'Cote TVA',
                'vat_rates_desc' => 'Gestionează cotele TVA folosite în procesul de facturare',
                'vat_configured' => 'Cote TVA Configurate',
                'add_new_vat' => 'Adaugă Cotă TVA Nouă',
                'breadcrumb_settings' => 'Setări'
            ],
            'messages' => [
                'delete_vat_confirm' => 'Ești sigur că vrei să ștergi această cotă TVA?'
            ]
        ];
    }
}
