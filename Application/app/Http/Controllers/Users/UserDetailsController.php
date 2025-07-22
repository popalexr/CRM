<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Traits\HasFormLabels;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserDetailsController extends Controller
{
    use HasFormLabels;

    private int $userId;
    private ?User $user = null;

    public function __construct(private Request $request)
    {
        if ($this->request->has('id')) {
            $this->userId = (int) $this->request->input('id');
            
            $this->user = User::find($this->userId);
        }
    }
    
    public function __invoke()
    {
        if (!$this->user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        $userData = $this->user->toArray();
        $userData['permissions'] = $this->getUserPermissions();
        
        return Inertia::render('Users/Show', [
            'user' => $userData,
            'formLabels' => $this->getFormLabels('users', 'user_forms.detail_labels'),
        ]);
    }
    
    private function getAllAvailablePermissionsGrouped(): array
    {
        $permissionLabels = config('permissions');
        $groupedPermissions = [];
        
        foreach ($permissionLabels as $category => $categoryPermissions) {
            $groupedPermissions[$category] = [];
            foreach ($categoryPermissions as $permission => $label) {
                $groupedPermissions[$category][] = $label;
            }
        }
        
        return $groupedPermissions;
    }
    
    private function getUserPermissionsGrouped(array $userPermissions): array
    {
        $permissionLabels = config('permissions');
        $groupedPermissions = [];
        
        foreach ($permissionLabels as $category => $categoryPermissions) {
            $groupedPermissions[$category] = [];
            foreach ($userPermissions as $permission) {
                if (isset($categoryPermissions[$permission])) {
                    $groupedPermissions[$category][] = $categoryPermissions[$permission];
                }
            }
            if (empty($groupedPermissions[$category])) {
                unset($groupedPermissions[$category]);
            }
        }
        
        return $groupedPermissions;
    }
    
    private function getReadablePermissions(array $permissions): array
    {
        $permissionLabels = config('permissions');
        $readablePermissions = [];
        
        foreach ($permissions as $permission) {
            foreach ($permissionLabels as $category => $categoryPermissions) {
                if (isset($categoryPermissions[$permission])) {
                    $readablePermissions[] = $categoryPermissions[$permission];
                    break;
                }
            }
        }
        
        return $readablePermissions;
    }

    private function getUserPermissions(): array
    {
        if ($this->user->isAdmin()) {
            return $this->getAllAvailablePermissionsGrouped();
        }

        return $this->getUserPermissionsGrouped($this->user->getAllPermissions());
    }
}
