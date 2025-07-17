<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersFormController extends Controller
{
    private ?User $user;
    private ?int $userId;

    public function __construct(private Request $request)
    {
        $this->userId = $request->input('id');

        if ($this->userId) {
            $this->user = User::find($this->userId);
        } else {
            $this->user = null;
        }
    }

    /**
     * Display the form for creating or editing a user.
     */
    public function __invoke()
    {
        if ($this->userId > 0 && !$this->user) {
            return redirect()
                ->route('users.index')
                ->with('errors', 'User not found.');
        }

        if (!$this->userId) {
            return Inertia::render('Users/Create', [
                'availablePermissions' => $this->getAvailablePermissions(),
                'formData'             => $this->getFormData(),
            ]);
        }

        return Inertia::render('Users/Edit', [
            'user'                  => $this->getUserInfo(),
            'availablePermissions'  => $this->getAvailablePermissions(),
            'formData'              => $this->getFormData(),
        ]);
    }

    /**
     * Get available permissions from config organized for frontend.
     */
    private function getAvailablePermissions(): array
    {
        $permissions = config('permissions');
        $categories = [];
        $availablePermissions = [];

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

    /**
     * Get the users information for the form.
     */
    public function getUserInfo(): array
    {
        if (!$this->user) {
            return [];
        }

        return [
            'id'          => $this->user->id,
            'name'        => $this->user->name,
            'email'       => $this->user->email,
            'phone'       => $this->user->phone,
            'birth_date'  => $this->user->birth_date,
            'address'     => $this->user->address,
            'city'        => $this->user->city,
            'county'      => $this->user->county,
            'country'     => $this->user->country,
            'permissions' => $this->user->getAllPermissions(),
        ];
    }

    /**
     * Get the form data configuration.
     */
    private function getFormData(): array
    {
        return [
            'labels' => __('users.labels'),
            'placeholders' => __('users.placeholders'),
            'tabs' => __('users.tabs'),
            'buttons' => __('users.buttons'),
            'messages' => __('users.messages'),
            'config' => config('user_forms'),
        ];
    }
}
