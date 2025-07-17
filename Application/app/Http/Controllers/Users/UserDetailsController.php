<?php

namespace App\Http\Controllers\Users;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facdes\Redirect;
class UserDetailsController extends Controller
{
    public function __invoke(User $user)
    {
        
        ////$user->load(['__invoke' => function($query) {
           // $query->orderByDesc('issue_date')->limit(10); 
       // }]);
        return Inertia::render('Users/Show', [
            'user' => $user, 
        ]);
    }
}
