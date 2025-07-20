<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'birth_date',
        'address',
        'city',
        'county',
        'country',
        'permissions',
        'is_admin',
        'profile_gradient_preference',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'deleted_at' => 'datetime',
            'birth_date' => 'date',
            'permissions' => 'array',

        ];
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getAllPermissions());
    }

    /**
     * Get all permissions of the user.
     *
     * @return array<string>
     */
    public function getAllPermissions(): array
    {
        $permissions = $this->permissions;
        
        if (is_string($permissions)) {
            $decoded = json_decode($permissions, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return is_array($permissions) ? $permissions : [];
    }

    public function getFullAddressAttribute(): string
    {
        $parts = [];
        if ($this->address) $parts[] = $this->address;
        if ($this->city) $parts[] = $this->city;
        if ($this->county) $parts[] = $this->county;
        if ($this->country) $parts[] = $this->country;
        return implode(', ', $parts);
    }

   
}
