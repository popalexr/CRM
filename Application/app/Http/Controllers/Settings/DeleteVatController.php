<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\VAT;
use Illuminate\Http\Request;

class DeleteVatController extends Controller
{
    private int $vatId;
    private ?VAT $vatRate;

    public function __construct(private Request $request)
    {
        $this->vatId = $request->input('id');
        $this->vatRate = VAT::find($this->vatId);
    }

    public function __invoke()
    {
        if (blank($this->vatId)) {
            return redirect()->route('settings.vat')->with('error', 'VAT rate not found.');
        }

        if (blank($this->vatRate)) {
            return redirect()->route('settings.vat')->with('error', 'VAT rate not found.');
        }

        $this->vatRate->update(['deleted_at' => now()]);

        return redirect()->route('settings.vat')->with('success', 'VAT rate deleted successfully.');
    }
}
