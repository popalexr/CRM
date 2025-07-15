<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon; 
use Illuminate\Support\Facades\Redirect;

class DeleteUserController extends Controller
{
    
    public function __invoke(Request $request)
    {
      
        $userId = $request->input('id');
        if (! $userId || ! is_numeric($userId)) {
            return Redirect::route('users.index')->with('error', 'ID utilizator invalid.');
        }

        $user = User::find($userId);

        if (! $user) {
            return Redirect::route('users.index')->with('error', 'Utilizatorul nu a fost găsit.');
        }

        $user->delete(); 
        return Redirect::route('users.index')->with('success', 'Utilizatorul a fost șters cu succes.');
    }
}
