<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon; 

class DeleteUserController extends Controller
{
    private ?User $user = null;

    public function __construct(private Request $request)
    {
        if ($this->request->input('id')) {
            $this->user = User::find($this->request->input('id'));
        }
    }

    public function __invoke()
    {
        if (! $this->user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        if ($this->user->id === $this->request->user()->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        if ($this->user->isAdmin()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete an admin user.');
        }

        $this->user->deleted_at = Carbon::now();
        $this->user->save();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
