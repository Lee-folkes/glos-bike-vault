<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use App\Enums\UserRole;

class User extends Authenticatable
{
    use TwoFactorAuthenticatable;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Add role to fillable
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
            'role' => UserRole::class, // Cast role to UserRole enum
        ];
    }

    // Helper method to make checking roles easier
    public function hasRole(UserRole $role): bool
    {
        return $this->role === $role;
    }


    /*
     * Get the bikes for the user.
     */
    public function bikes()
    {
        return $this->hasMany(Bike::class);
    }


    /**
     * Get all stolen bikes in the system.
     * Only accessible to users with the ADMIN role.
     */
    public function getAllStolenBikes()
    {
        if (! $this->hasRole(UserRole::ADMIN)) {
            abort(403, 'Unauthorized action.');
        }

        return Bike::with('user')->whereIn('status', ['stolen', 'recovered'])->latest('stolen_at')->get();
    }

    

}
