<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clients\ContactFormRequest;
use App\Models\ClientContacts;
use App\Models\Clients;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClientContactsController extends Controller
{
    /**
     * Store a new contact for a client.
     */
    public function store(ContactFormRequest $request, int $clientId): JsonResponse
    {
        $client = Clients::find($clientId);
        
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        $contact = ClientContacts::create([
            'client_id' => $clientId,
            'contact_name' => $request->contact_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'contact_position' => $request->contact_position,
        ]);

        return response()->json([
            'message' => 'Contact created successfully',
            'contact' => $contact,
        ], 201);
    }

    /**
     * Update an existing contact.
     */
    public function update(ContactFormRequest $request, int $clientId, int $contactId): JsonResponse
    {
        $contact = ClientContacts::where('client_id', $clientId)
            ->where('id', $contactId)
            ->first();

        if (!$contact) {
            return response()->json(['error' => 'Contact not found'], 404);
        }

        $contact->update([
            'contact_name' => $request->contact_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'contact_position' => $request->contact_position,
        ]);

        return response()->json([
            'message' => 'Contact updated successfully',
            'contact' => $contact,
        ]);
    }

    /**
     * Delete a contact.
     */
    public function destroy(int $clientId, int $contactId): JsonResponse
    {
        $contact = ClientContacts::where('client_id', $clientId)
            ->where('id', $contactId)
            ->first();

        if (!$contact) {
            return response()->json(['error' => 'Contact not found'], 404);
        }

        $contact->delete();

        return response()->json([
            'message' => 'Contact deleted successfully',
        ]);
    }

    /**
     * Get all contacts for a client.
     */
    public function index(int $clientId): JsonResponse
    {
        $client = Clients::find($clientId);
        
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        $contacts = ClientContacts::where('client_id', $clientId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'contacts' => $contacts,
        ]);
    }
}
