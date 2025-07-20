<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Traits\HasFormLabels;
use Inertia\Inertia;

class ClientsController extends Controller
{
    use HasFormLabels;
    /**
     * Display the list of clients.
     */
    public function __invoke()
    {
        $clientsPaginated = $this->getClients();

        return Inertia::render('Clients/Index', [
            'clients' => $clientsPaginated['data'],
            'meta' => [
                'current_page' => $clientsPaginated['current_page'],
                'per_page' => $clientsPaginated['per_page'],
                'total' => $clientsPaginated['total'],
                'last_page' => $clientsPaginated['last_page'],
            ],
            'formLabels' => $this->getFormLabels('clients'),
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
            ->paginate(10);

        return $clients->toArray();
    }
}
