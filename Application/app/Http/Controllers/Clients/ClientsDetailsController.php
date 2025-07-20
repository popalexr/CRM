<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\ClientContacts;
use App\Traits\HasFormLabels;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientsDetailsController extends Controller
{
    use HasFormLabels;
    
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
                ->with('error', __('clients.messages.client_not_found'));
        }

        return Inertia::render('Clients/Show' ,[
            'client'         => $this->client,
            'clientContacts' => $this->getClientContacts(),
            'formLabels'     => $this->getFormLabels('clients'),
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
