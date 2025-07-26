<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Invoices;
use App\Models\Clients;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserInvoicesController extends Controller
{
    public function __invoke(Request $request)
    {
        $userId = (int) $request->input('id', 0);
        $user = User::findOrFail($userId);
        $invoices = Invoices::where('created_by', $userId)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($invoice) {
                $invoice['client'] = Clients::find($invoice['client_id']);
                return $invoice;
            });
        return response()->json(['invoices' => $invoices]);
    }
}
