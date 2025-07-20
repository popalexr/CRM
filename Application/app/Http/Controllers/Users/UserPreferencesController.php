<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserPreferencesController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'preference_type' => 'required|string|in:profile_gradient',
            'preference_value' => 'required|integer|min:0|max:7'
        ]);

        $user = User::findOrFail($request->user_id);
        
        switch ($request->preference_type) {
            case 'profile_gradient':
                $user->profile_gradient_preference = $request->preference_value;
                break;
        }
        
        $user->save();

        return response('', 200);
    }
}
