<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Traits\HasFormLabels;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class UserDetailsController extends Controller
{
    use HasFormLabels;
    
    public function __invoke(User $user)
    {
        $permissions = $user->is_admin 
            ? $this->getAllAvailablePermissionsGrouped() 
            : $this->getUserPermissionsGrouped($user->getAllPermissions());
        
        $userData = $user->toArray();
        $userData['permissions'] = $permissions;
        
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
}
