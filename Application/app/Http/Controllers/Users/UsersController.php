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
        app()->setLocale('en');
        // default until we make it dynamic from user settings or preferences
        
        $formConfig = config('user_forms');
        
        $formData = [
            'labels' => __('users.labels'),
            'placeholders' => __('users.placeholders'), 
            'tabs' => __('users.tabs'),
            'buttons' => __('users.buttons'),
            'messages' => __('users.messages'),
            'config' => $formConfig,
        ];
        
        return Inertia::render('Users/Index', [
            'users' => User::all(),
            'formData' => $formData,
        ]);
    }

    // To Do part in "To Do Tasks Nr. #458"
}