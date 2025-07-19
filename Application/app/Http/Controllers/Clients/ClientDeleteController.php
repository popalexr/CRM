<?php
namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clients;
use Illuminate\Support\Carbon;

class ClientDeleteController extends Controller
{
    private ?Clients $client = null;

    public function __construct(private Request $request)
    {
        if ($this->request->has('id')) {
            $this->client = Clients::find($this->request->input('id'));
        }
    }

    public function __invoke(Request $request)
    {
        if (blank($this->client)) {
            return redirect()
                ->route('clients.index')
                ->with('error', __('clients.messages.client_not_found'));
        }

        $this->client->deleted_at = Carbon::now();
        $this->client->save();

        return redirect()->route("clients.index")->with('success', __('clients.messages.client_deleted'));
    }
}
