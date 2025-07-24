<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use Illuminate\Http\Request;

class GetClientsController extends Controller
{
    public function __construct(private Request $request) {}

    public function __invoke()
    {
        $clients = Clients::query()
            ->select(['id', 'client_name'])
            ->orderBy('client_name')
            ->when($this->request->input('q'), function ($query) {
                $search = $this->request->input('q');
                $query->where('client_name', 'like', "%{$search}%");
            })
            ->get(10);

        return response()->json($clients);
    }
}
