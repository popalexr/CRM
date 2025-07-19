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
                ->with('error', __('clients.messages.client_not_found'));
        }

        return Inertia::render('Clients/Show' ,[
            'client'         => $this->client,
            'clientContacts' => $this->getClientContacts(),
            'formLabels'     => $this->getFormLabels(),
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

    private function getFormLabels(): array
    {
        return [
            'labels' => __('clients.labels'),
            'placeholders' => __('clients.placeholders'),
            'tabs' => __('clients.tabs'),
            'buttons' => __('clients.buttons'),
            'headings' => __('clients.headings'),
            'messages' => __('clients.messages'),
            'client_types' => __('clients.client_types'),
            'status' => __('clients.status'),
        ];
    }
}
