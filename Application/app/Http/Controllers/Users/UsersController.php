<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function __invoke()
    {
        // Get form constants from config
        $formConstants = config('user_forms');
        
        return Inertia::render('Users/Index', [
            'users' => User::all(),
            'formConstants' => $formConstants,
        ]);
    }

    // To Do part in "To Do Tasks Nr. #458"
}