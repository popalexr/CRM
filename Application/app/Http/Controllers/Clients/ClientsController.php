<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\ClientContacts;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientsController extends Controller
{
    /**
     * Display the list of clients.
     */
    public function index()
    {
        $clients = $this->getClients();

        return Inertia::render('Clients/Index', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'clientType' => 'required|in:individual,company',
            'cui' => 'nullable|string|max:255',
            'registrationNumber' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'bank' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'county' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:255',
            'contactPersons' => 'nullable|array',
            'contactPersons.*.name' => 'required|string|max:255',
            'contactPersons.*.email' => 'nullable|email|max:255',
            'contactPersons.*.phone' => 'nullable|string|max:255',
        ]);

        // Map client type values to match database enum
        $clientType = $validated['clientType'] === 'company' ? 'business' : 'individual';

        // Create the client
        $client = Clients::create([
            'client_name' => $validated['name'],
            'client_logo' => '', // Default empty logo
            'client_type' => $clientType,
            'client_email' => $validated['email'] ?? null,
            'client_phone' => $validated['phone'] ?? null,
            'cui' => $validated['cui'] ?? null,
            'registration_number' => $validated['registrationNumber'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'county' => $validated['county'] ?? null,
            'country' => $validated['country'] ?? null,
            'bank_name' => $validated['bank'] ?? null,
            'iban' => $validated['iban'] ?? null,
            'currency' => $validated['currency'] ?? 'RON',
        ]);

        // Create contact persons if provided
        if (isset($validated['contactPersons']) && is_array($validated['contactPersons'])) {
            foreach ($validated['contactPersons'] as $contactData) {
                ClientContacts::create([
                    'client_id' => $client->id,
                    'name' => $contactData['name'],
                    'email' => $contactData['email'] ?? null,
                    'phone' => $contactData['phone'] ?? null,
                ]);
            }
        }

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Clients $client)
    {
        return Inertia::render('Clients/Edit', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Clients $client)
    {
        // Implementation for updating client
        // Similar to store method but for updating
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Clients $client)
    {
        $client->update(['deleted_at' => now()]);
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }

    /**
     * Get the list of clients from database.
     *
     * @return array
     */
    private function getClients(): array
    {
        $clients = Clients::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return $clients->toArray();
    }
    public function show(Clients $client) 
    {
        
        $client->load('contactPersons');
        return Inertia::render('Clients/Show', [
            'client' => $client,
           
        ]);
    }
}
