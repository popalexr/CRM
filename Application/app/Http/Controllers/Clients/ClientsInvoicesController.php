<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Invoices;
use Illuminate\Http\Request;

class ClientsInvoicesController extends Controller
{
    public function __invoke(Request $request)
    {
        $clientId = $request->input('id');
        if (!$clientId) {
            return response()->json(['invoices' => []]);
        }
        $invoices = Invoices::where('client_id', $clientId)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json(['invoices' => $invoices]);
    }
}
