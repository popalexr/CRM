<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clients\ClientsFormRequest;
use App\Models\ClientContacts;
use App\Models\Clients;
use App\Models\TemporaryFiles;
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
            return Inertia::render('Clients/Create', [
                'formLabels' => $this->getFormLabels(),
            ]);
        }

        if (blank($this->client))
        {
            return redirect()
                ->route('clients.index')
                ->with('error', __('clients.messages.client_not_found'));
        }
        
        return Inertia::render('Clients/Edit', [
            'client'         => $this->client,
            'clientContacts' => $this->getContactPersons(),
            'formLabels'     => $this->getFormLabels(),
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

            if ($this->clientHasNewLogo($request)) {
                $this->saveClientLogo($request->input('logo_file_id'));
            }
            
            return redirect()
                ->route('clients.index')
                ->with('success', __('clients.messages.client_created'));
        }

        if ($this->clientId > 0 && blank($this->client))
        {
            return redirect()
                ->route('clients.index')
                ->with('error', __('clients.messages.client_not_found'));
        }

        $this->handleUpdateClient($request);

        if ($this->clientHasNewLogo($request)) {
            $this->saveClientLogo($request->input('logo_file_id'));
        }
        
        return redirect()
            ->route('clients.index')
            ->with('success', __('clients.messages.client_updated'));
    }

    /**
     * Handle the creation of a new client.
     */
    private function handleCreateClient(ClientsFormRequest $request)
    {
        $isClientTypeBusiness = $request->input('client_type') === 'business';

        $this->client = Clients::create([
            'client_name'         => $request->input('client_name'),
            'client_logo'         => null, // Set to null before saving, will be updated later if a logo is provided
            'client_type'         => $request->input('client_type'),
            'cui'                 => $isClientTypeBusiness ? $request->input('cui') : $request->input('cnp'),
            'registration_number' => $isClientTypeBusiness ? $request->input('registrationNumber') : null,
            'client_email'        => $request->input('email'),
            'client_phone'        => $request->input('phone'),
            'address'             => $request->input('address'),
            'country'             => $request->input('country'),
            'county'              => $request->input('county'),
            'city'                => $request->input('city'),
            'iban'                => $request->input('iban'),
            'swift'               => $request->input('swift', null),
            'bank_name'           => $request->input('bank'),
            'currency'            => $request->input('currency'),
            'notes'               => $request->input('notes', null),
            'client_tva'          => $isClientTypeBusiness ? $request->boolean('client_tva') : true,

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
        $isClientTypeBusiness = $request->input('client_type') === 'business';

        $this->client->update([
            'client_name'         => $request->input('client_name'),
            'client_type'         => $request->input('client_type'),
            'cui'                 => $isClientTypeBusiness ? $request->input('cui') : $request->input('cnp'),
            'registration_number' => $isClientTypeBusiness ? $request->input('registrationNumber') : null,
            'client_email'        => $request->input('email'),
            'client_phone'        => $request->input('phone'),
            'address'             => $request->input('address'),
            'country'             => $request->input('country'),
            'county'              => $request->input('county'),
            'city'                => $request->input('city'),
            'iban'                => $request->input('iban'),
            'swift'               => $request->input('swift', null),
            'bank_name'           => $request->input('bank'),
            'currency'            => $request->input('currency'),
            'notes'               => $request->input('notes', null),
            'client_tva'          => $isClientTypeBusiness ? $request->boolean('client_tva') : true,

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

    /**
     * Check if the client has a new logo.
     */
    private function clientHasNewLogo($request): bool
    {
        if (is_null($request->input('logo_file_id'))) {
            return false;
        }

        if ($request->input('logo_file_id') < 1) {
            return false;
        }

        $tmp = TemporaryFiles::find($request->input('logo_file_id'));

        return ! blank ($tmp) && file_exists(storage_path($tmp->file_path));
    }

    /**
     * Save the new logo for the client.
     */
    private function saveClientLogo($fileId): void
    {
        $tmp = TemporaryFiles::find($fileId);

        if (blank($tmp) || ! file_exists(storage_path($tmp->file_path))) {
            return;
        }

        $tmp_storage = storage_path($tmp->file_path);

        $new_storage = storage_path('app/public/clients/' . $tmp->file_name);

        if (! file_exists($new_storage)) {
            copy($tmp_storage, $new_storage);
        }

        $this->client->client_logo = '/storage/clients/' . $tmp->file_name;

        $this->client->save();
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
