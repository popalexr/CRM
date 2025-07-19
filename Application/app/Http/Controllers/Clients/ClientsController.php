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
            'clients' => $clients['data'],
            'meta' => [
                'current_page' => $clients['current_page'],
                'per_page' => $clients['per_page'],
                'total' => $clients['total'],
                'last_page' => $clients['last_page'],
            ],
            'formLabels' => $this->getFormLabels(),
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
