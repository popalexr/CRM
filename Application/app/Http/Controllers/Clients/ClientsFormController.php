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
        $id = null;
        
        // For GET requests, check query parameters
        if ($this->request->isMethod('GET') && $this->request->has('id')) {
            $id = $this->request->input('id');
        }
        
        // For POST requests, check request body
        if ($this->request->isMethod('POST') && $this->request->has('id')) {
            $id = $this->request->input('id');
        }
        
        \Log::info('ClientsFormController::__construct - Debug info:', [
            'method' => $this->request->method(),
            'has_id_query' => $this->request->has('id'),
            'id_value' => $id,
            'id_as_int' => (int) $id,
            'all_request' => $this->request->all()
        ]);
        
        if ($id && (int) $id > 0) {
            $this->clientId = (int) $id;
            $this->client = Clients::find($this->clientId);
            
            \Log::info('Client loaded in constructor:', [
                'clientId' => $this->clientId,
                'client_found' => !blank($this->client),
                'client_data' => $this->client ? $this->client->toArray() : null
            ]);
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
    public function post(ClientsFormRequest $request, $id = null)
    {
        // Use route parameter if available, otherwise use form data
        $clientId = $id ?: $request->input('id');
        
        // Debug information
        \Log::info('ClientsFormController::post - Debug info:', [
            'route_id' => $id,
            'form_id' => $request->input('id'),
            'final_clientId' => $clientId,
            'constructor_clientId' => $this->clientId,
            'client_exists' => !blank($this->client),
            'all_request_data' => $request->all()
        ]);
        
        // Override constructor values with route parameter if available
        if ($clientId && (int) $clientId > 0) {
            $this->clientId = (int) $clientId;
            if (!$this->client) {
                $this->client = Clients::find($this->clientId);
            }
        }
        
        // If no client ID is provided, create a new client
        if ($this->clientId < 1)
        {
            \Log::info('Creating new client');
            $this->handleCreateClient($request);
            
            return redirect()
                ->route('clients.index')
                ->with('success', 'Client created successfully.');
        }

        // If client ID is provided but client doesn't exist, show error
        if ($this->clientId > 0 && blank($this->client))
        {
            \Log::error('Client ID provided but client not found', ['clientId' => $this->clientId]);
            return redirect()
                ->route('clients.index')
                ->with('error', 'Client not found.');
        }

        // Update existing client
        \Log::info('Updating existing client', ['clientId' => $this->clientId]);
        $this->handleUpdateClient($request);
        
        return redirect()
            ->route('clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Handle the creation of a new client.
     */
    private function handleCreateClient(ClientsFormRequest $request)
    {
        $client = Clients::create([
            'client_name'         => $request->input('client_name'),
            'client_logo'         => null, // Set to null for now, can be updated later when logo upload is implemented
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
