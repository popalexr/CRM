<?php
namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clients;
use Illuminate\Support\Carbon;

class ClientDeleteController extends Controller
{
    public function __invoke(Request $request)
    {
        $clientId = $request->input('id');

        $client = Clients::findOrFail($clientId);
        $client->deleted_at = Carbon::now(); // setează data și ora curentă
        $client->save();

        return redirect()->back()->with('success', 'Clientul a fost marcat ca șters.');
    }
}