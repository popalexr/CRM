<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UsersFormRequest;
use App\Models\TemporaryFiles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
                ->with('error', 'User not found.');
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
     * Handle the form submission for creating or updating a user.
     */
    public function post(UsersFormRequest $request)
    {
        if ($this->userId > 0 && !$this->user) {
            return redirect()
                ->route('users.index')
                ->with('error', 'User not found.');
        }

        if (! $this->userId)
        {
            return $this->handleCreateUser($request);
        }

        return $this->handleUpdateUser($request);
    }

    /**
     * Handle the creation of a new user.
     */
    private function handleCreateUser(UsersFormRequest $request)
    {
        $validatedRequest = $request->validated();
        $userData = [
            'name'              => $validatedRequest['name'],
            'email'             => $validatedRequest['email'],
            'phone'             => $validatedRequest['phone'] ?? null,
            'birth_date'        => $validatedRequest['birth_date'] ?? null,
            'address'           => $validatedRequest['address'] ?? null,
            'city'              => $validatedRequest['city'] ?? null,
            'county'            => $validatedRequest['county'] ?? null,
            'country'           => $validatedRequest['country'] ?? null,
            'permissions'       => json_encode($validatedRequest['permissions'] ?? []),
            'password'          => bcrypt(Str::random(12)), // Generate a random password
        ];

        try {
            DB::beginTransaction();

            $this->user = User::create($userData);
            $this->user->save();

            if ($validatedRequest['avatar_file_id']) {
                $this->saveUserAvatar($validatedRequest['avatar_file_id']);
            }

            DB::commit();

            return redirect()
                ->route('users.index')
                ->with('success', 'User created successfully.');
        }
        catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Failed to create user.');
        }
    }

    /**
     * Handle the update of an existing user.
     */
    private function handleUpdateUser(UsersFormRequest $request)
    {
        $validatedRequest = $request->validated();
    
        $userData = [
            'name'              => $validatedRequest['name'],
            'email'             => $validatedRequest['email'],
            'phone'             => $validatedRequest['phone'] ?? null,
            'birth_date'        => $validatedRequest['birth_date'] ?? null,
            'address'           => $validatedRequest['address'] ?? null,
            'city'              => $validatedRequest['city'] ?? null,
            'county'            => $validatedRequest['county'] ?? null,
            'country'           => $validatedRequest['country'] ?? null,
            'permissions'       => json_encode($validatedRequest['permissions'] ?? []),
        ];

        try {
            DB::beginTransaction();

            $this->user->update($userData);
            $this->user->save();

            if ($validatedRequest['avatar_file_id']) {
                $this->saveUserAvatar($validatedRequest['avatar_file_id']);
            }

            return redirect()
                ->route('users.index')
                ->with('success', 'User updated successfully.');
        }
        catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Failed to update user.');
        }
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

    /**
     * Save user avatar if provided.
     */
    private function saveUserAvatar(int $fileId): void
    {
        $tmp = TemporaryFiles::find($fileId);

        if (!$tmp) {
            return;
        }

        $tmp_storage = storage_path($tmp->file_path);

        $new_storage = storage_path('app/public/users/' . $tmp->file_name);

        if (! file_exists($new_storage)) {
            copy($tmp_storage, $new_storage);
        }

        $this->user->avatar = '/storage/users/' . $tmp->file_name;
        $this->user->save();
    }
}
