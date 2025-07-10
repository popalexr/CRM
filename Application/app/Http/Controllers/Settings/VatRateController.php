<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VatRateController extends Controller
{
    /**
     * Store a new VAT rate.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'rate' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        
        $vatId = Str::slug($data['name']) . '_' . time();

        Setting::create([
            'key' => 'vat_rate_' . $vatId,
            'type' => 'number',
            'value' => (string) $data['rate']
        ]);

        Setting::create([
            'key' => 'vat_rate_' . $vatId . '_name',
            'type' => 'string',
            'value' => $data['name']
        ]);

        return back()->with('success', 'Cota TVA a fost adăugată cu succes.');
    }

    /**
     * Delete a VAT rate.
     */
    public function destroy(Request $request, string $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        Setting::where('key', 'vat_rate_' . $id)->delete();
        Setting::where('key', 'vat_rate_' . $id . '_name')->delete();

        return back()->with('success', 'Cota TVA a fost ștearsă cu succes.');
    }
}
