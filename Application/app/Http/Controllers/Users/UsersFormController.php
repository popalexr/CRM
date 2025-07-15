<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersFormController extends Controller
{
    /**
     * Display the form for creating or editing a user.
     */
    public function __invoke(Request $request)
    {
        $availablePermissions = $this->getAvailablePermissions();
        
        $formConstants = config('user_forms');
        
        $userId = $request->input('id');
        
        if (empty($userId) || $userId == 0) {
            return Inertia::render('Users/Create', [
                'availablePermissions' => $availablePermissions,
                'formConstants' => $formConstants,
            ]);
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()
                ->route('users.index')
                ->with('errors', 'User not found');
        }
        
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'birth_date' => $user->birth_date,
                'address' => $user->address,
                'city' => $user->city,
                'county' => $user->county,
                'country' => $user->country,
                'permissions' => $user->getAllPermissions(),
            ],
            'availablePermissions' => $availablePermissions,
            'formConstants' => $formConstants,
        ]);
    }

    /**
     * Get available permissions from config organized for frontend.
     */
    private function getAvailablePermissions(): array
    {
        $permissions = config('permissions');
        $categories = $permissions['categories'] ?? [];
        $availablePermissions = [];

        unset($permissions['categories']);

        foreach ($permissions as $category => $categoryPermissions) {
            foreach ($categoryPermissions as $id => $label) {
                $availablePermissions[] = [
                    'id' => $id,
                    'label' => $label,
                    'category' => $categories[$category] ?? $category,
                ];
            }
        }

        return $availablePermissions;
    }
}
