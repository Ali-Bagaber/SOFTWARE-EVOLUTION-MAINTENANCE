<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role',
        'contact_number',
        'profile_picture',
        'agency_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Set the password attribute, hashing it if it's not already hashed.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        // Only hash if not already hashed (avoid double hashing)
        if (strlen($value) === 60 && preg_match('/^\$2y\$/', $value)) {
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password'] = \Illuminate\Support\Facades\Hash::make($value);
        }
    }

    // Helper methods to check user roles
    public function isAdmin()
    {
        return $this->user_role === 'admin' || $this->email === 'admin@admin.com';
    }

    public function isAgency()
    {
        return str_ends_with($this->email, '@agency') || str_ends_with($this->email, '@agency.com');
    }

    public function isPublicUser()
    {
        return !$this->isAdmin() && !$this->isAgency();
    }
    
    /**
     * Check if the user is using a default password or needs to reset
     * Default password matches the following criteria:
     * - User has never logged in before (last_login_at is null)
     * - Password has not been changed from default (session flag not set)
     * Note: contact_number check is handled separately in login logic
     */
    public function hasDefaultPassword()
    {
        // If the user has never logged in, consider it a default password situation
        if (is_null($this->last_login_at)) {
            return true;
        }
        
        // Check if the 'password_changed' session flag is not set
        if (!session()->has('password_changed_'.$this->user_id)) {
            return true;
        }
        
        return false;
    }

    /**
     * Get the agency that this user belongs to.
     */
    public function agency()
    {
        return $this->belongsTo(\App\Models\Agency::class, 'agency_id', 'agency_id');
    }
}