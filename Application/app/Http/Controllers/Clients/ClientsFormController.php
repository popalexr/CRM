<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clients\ClientsFormRequest;
use App\Models\ClientContacts;
use App\Models\Clients;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientsFormController extends Controller
{
    private int $clientId = 0;
    private ?Clients $client = null;

    public function __construct(private Request $request)
    {
        if ($this->request->has('id')) {
            $this->client = Clients::find($this->request->input('id'));

            $this->clientId = (int) $this->request->input('id');
        }
    }
    
    /**
     * Display the form for creating or editing a client.
     */
    public function __invoke()
    {
        if ($this->clientId < 1)
        {
            return Inertia::render('Clients/Create');
        }

        if (blank($this->client)) // If client does not exist, show an error
        {
            return redirect()
                ->route('clients.index')
                ->with('error', 'Client not found.');
        }
        
        return Inertia::render('Clients/Edit', [
            'client' => $this->client,
        ]);
    }

    /**
     * Handle the form submission for creating or editing a client.
     */
    public function post(ClientsFormRequest $request)
    {
        if (blank($this->client))
        {
            $this->handleCreateClient($request);
            
            return;
        }

        if ($this->clientId > 0 && blank($this->client)) // Check if client exists
        {
            return redirect()
                ->route('clients.index')
                ->with('error', 'Client not found.');
        }

        $this->handleUpdateClient($request);
    }

    /**
     * Handle the creation of a new client.
     */
    private function handleCreateClient(ClientsFormRequest $request)
    {
        $client = Clients::create([
            'client_name'         => $request->input('client_name'),
            'client_type'         => $request->input('client_type'),
            'cui'                 => $request->input('cui'),
            'registration_number' => $request->input('registrationNumber'),
            'client_email'        => $request->input('email'),
            'client_phone'        => $request->input('phone'),
            'address'             => $request->input('address'),
            'country'             => $request->input('country'),
            'county'              => $request->input('county'),
            'city'                => $request->input('city'),
            'iban'                => $request->input('iban'),
            'bank_name'           => $request->input('bank'),
            'currency'            => $request->input('currency'),
            'notes'               => $request->input('notes', null),
        ]);

        if (! ($request->has('contactPersons') && is_array($request->input('contactPersons')))) { // If no contact persons are provided, return
            return;
        }

        // Create contact persons for the client
        foreach ($request->input('contactPersons') as $contact) {
            $client->contactPersons()->create([
                'contact_name'  => $contact['name'],
                'contact_email' => $contact['email'] ?? null,
                'contact_phone' => $contact['phone'] ?? null,
                'contact_position' => $contact['position'] ?? null,
            ]);
        }
    }

    /**
     * Handle the update of an existing client.
     */
    private function handleUpdateClient(ClientsFormRequest $request)
    {
        $this->client->update([
            'client_name'         => $request->input('name'),
            'client_type'         => $request->input('clientType'),
            'cui'                 => $request->input('cui'),
            'registration_number' => $request->input('registrationNumber'),
            'client_email'        => $request->input('email'),
            'client_phone'        => $request->input('phone'),
            'address'             => $request->input('address'),
            'country'             => $request->input('country'),
            'county'              => $request->input('county'),
            'city'                => $request->input('city'),
            'iban'                => $request->input('iban'),
            'bank_name'           => $request->input('bank'),
            'currency'            => $request->input('currency'),
        ]);

        $this->deleteContactPersons();
        
        if (! ($request->has('contactPersons') && is_array($request->input('contactPersons')))) { // If no contact persons are provided, return
            return;
        }

        // Create contact persons for the client
        foreach ($request->input('contactPersons') as $contact) {
            $this->client->contactPersons()->create([
                'contact_name'  => $contact['name'],
                'contact_email' => $contact['email'] ?? null,
                'contact_phone' => $contact['phone'] ?? null,
                'contact_position' => $contact['position'] ?? null,
            ]);
        }
    }

    /**
     * Delete all contact persons associated with the client.
     */
    private function deleteContactPersons()
    {
        ClientContacts::where('client_id', $this->clientId)->delete();
    }
}
