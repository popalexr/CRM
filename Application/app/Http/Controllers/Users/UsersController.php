<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HasFormLabels;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersController extends Controller
{
    use HasFormLabels;
    /**
     * Display a listing of users.
     */
    public function __invoke()
    {
        app()->setLocale('en');
        // default until we make it dynamic from user settings or preferences
        
        $formData = $this->getFormData('users', 'user_forms', null, [
            'messages' => ['delete_dialog' => __('users.delete_dialog')]
        ]);
        
        $users = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'permissions' => $user->getAllPermissions(),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'is_admin' => $user->is_admin,
            ];
        });

        return Inertia::render('Users/Index', [
            'users' => $users,
            'formData' => $formData,
        ]);
    }

    // To Do part in "To Do Tasks Nr. #458"
}