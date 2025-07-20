<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UsersFormRequest;
use App\Models\TemporaryFiles;
use App\Models\User;
use App\Traits\HasFormLabels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UsersFormController extends Controller
{
    use HasFormLabels;
    
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
                'formData'             => $this->getFormData('users', 'user_forms'),
            ]);
        }

        return Inertia::render('Users/Edit', [
            'user'                  => $this->getUserInfo(),
            'availablePermissions'  => $this->getAvailablePermissions(),
            'formData'              => $this->getFormData('users', 'user_forms'),
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
        
        $userData = $this->mapFormDataToDatabase($validatedRequest, 'create', 'user_forms', [
            'permissions' => fn($permissions) => json_encode($permissions ?? []),
            'avatar_file_id' => fn($fileId) => null, // Handle separately
        ]);

        // Add generated password for new users
        $userData['password'] = bcrypt(Str::random(12));

        try {
            DB::beginTransaction();

            $this->user = User::create($userData);

            if ($validatedRequest['avatar_file_id'] ?? null) {
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
        
        $userData = $this->mapFormDataToDatabase($validatedRequest, 'update', 'user_forms', [
            'permissions' => fn($permissions) => json_encode($permissions ?? []),
            'avatar_file_id' => fn($fileId) => null, // Handle separately
        ]);

        try {
            DB::beginTransaction();

            $this->user->update($userData);

            if ($validatedRequest['avatar_file_id'] ?? null) {
                $this->saveUserAvatar($validatedRequest['avatar_file_id']);
            }

            DB::commit();

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
