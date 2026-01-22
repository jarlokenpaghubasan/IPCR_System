<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'password',
        'department_id',
        'designation_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * User roles relationship (one user can have multiple roles)
     */
    public function userRoles()
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * Get all roles for this user
     */
    public function roles()
    {
        return $this->userRoles()->pluck('role')->toArray();
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return in_array($role, $this->roles());
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles)
    {
        $userRoles = $this->roles();
        foreach ((array) $roles as $role) {
            if (in_array($role, $userRoles)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Assign a role to user
     */
    public function assignRole($role)
    {
        if (!$this->hasRole($role)) {
            UserRole::create([
                'user_id' => $this->id,
                'role' => $role,
            ]);
        }
    }

    /**
     * Remove a role from user
     */
    public function removeRole($role)
    {
        UserRole::where('user_id', $this->id)
            ->where('role', $role)
            ->delete();
    }

    /**
     * Get primary role (for display purposes)
     * Priority: admin > director > dean > faculty
     */
    public function getPrimaryRole()
    {
        $roles = $this->roles();
        $priority = ['admin', 'director', 'dean', 'faculty'];
        
        foreach ($priority as $role) {
            if (in_array($role, $roles)) {
                return $role;
            }
        }
        
        return $roles[0] ?? null;
    }

    /**
     * Relationships
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function photos()
    {
        return $this->hasMany(UserPhoto::class);
    }

    public function profilePhoto()
    {
        return $this->hasOne(UserPhoto::class)->where('is_profile_photo', true);
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        try {
            $profilePhoto = UserPhoto::where('user_id', $this->id)
                ->where('is_profile_photo', true)
                ->first();
            
            if ($profilePhoto && $profilePhoto->path) {
                $fullPath = storage_path("app/public/{$profilePhoto->path}");
                if (file_exists($fullPath)) {
                    return asset("storage/{$profilePhoto->path}");
                }
            }
        } catch (\Exception $e) {
            // Log error if needed
        }
        
        return asset('/images/default_avatar.jpg');
    }

    /**
     * Check if user has a profile photo
     */
    public function hasProfilePhoto()
    {
        try {
            $profilePhoto = UserPhoto::where('user_id', $this->id)
                ->where('is_profile_photo', true)
                ->first();
            
            if ($profilePhoto && $profilePhoto->path) {
                $fullPath = storage_path("app/public/{$profilePhoto->path}");
                return file_exists($fullPath);
            }
        } catch (\Exception $e) {
            // Log error if needed
        }
        
        return false;
    }
}