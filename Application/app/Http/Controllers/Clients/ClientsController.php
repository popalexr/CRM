<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use Inertia\Inertia;

class ClientsController extends Controller
{
    /**
     * Display the list of clients.
     */
    public function __invoke()
    {
        $clients = $this->getClients();

        return Inertia::render('Clients/Index', [
            'clients' => $clients,
        ]);
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
}
