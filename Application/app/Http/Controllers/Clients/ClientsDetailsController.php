<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\ClientContacts;
use App\Models\Clients;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientsDetailsController extends Controller
{
    private ?Clients $client = null;

    public function __construct(private Request $request)
    {
        if ($this->request->has('id')) {
            $this->client = Clients::find($this->request->input('id'));
        }
    }

    public function __invoke()
    {
        if (blank($this->client)) {
            return redirect()
                ->route('clients.index')
                ->with('error', 'Client not found.');
        }

        return Inertia::render('Clients/Show' ,[
            'client'         => $this->client,
            'clientContacts' => $this->getClientContacts(),
        ]);
    }

    private function getClientContacts(): array
    {
        if (blank($this->client)) {
            return [];
        }

        $clientContacts = ClientContacts::where('client_id', $this->client->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $clientContacts->toArray();
    }
}
