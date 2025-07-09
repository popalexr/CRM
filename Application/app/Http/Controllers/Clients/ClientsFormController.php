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
            $this->clientId = (int) $this->request->input('id');

            $this->client = Clients::find($this->clientId);
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
            'client'         => $this->client,
            'clientContacts' => $this->getContactPersons(),
        ]);
    }

    /**
     * Handle the form submission for creating or editing a client.
     */
    public function post(ClientsFormRequest $request)
    {
        // If no client ID is provided, create a new client
        if ($this->clientId < 1)
        {
            $this->handleCreateClient($request);
            
            return redirect()
                ->route('clients.index')
                ->with('success', 'Client created successfully.');
        }

        // If client ID is provided but client doesn't exist, show error
        if ($this->clientId > 0 && blank($this->client))
        {
            return redirect()
                ->route('clients.index')
                ->with('error', 'Client not found.');
        }

        // Update existing client
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
        $this->client = Clients::create([
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
            $this->addContactPerson($contact);
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
            $this->addContactPerson($contact);
        }
    }

    /**
     * Delete all contact persons associated with the client.
     */
    private function deleteContactPersons()
    {
        ClientContacts::where('client_id', $this->clientId)->delete();
    }

    /**
     * Add a new contact person to the client.
     */
    private function addContactPerson(array $contactData)
    {
        if (blank($this->client)) {
            return;
        }

        $contact = new ClientContacts([
            'client_id'         => $this->clientId,
            'contact_name'      => $contactData['name'],
            'contact_email'     => $contactData['email'],
            'contact_phone'     => $contactData['phone'],
            'contact_position'  => $contactData['position'] ?? null,
        ]);

        $contact->save();
    }

    /**
     * Get all contact persons associated with the client.
     */
    private function getContactPersons()
    {
        if (blank($this->client)) {
            return [];
        }

        return ClientContacts::where('client_id', $this->clientId)
            ->get()
            ->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->contact_name,
                    'email' => $contact->contact_email,
                    'phone' => $contact->contact_phone,
                    'position' => $contact->contact_position,
                ];
            })
            ->toArray();
    }
}
