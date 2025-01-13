<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
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
        ];
    }

    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    // Verifica si tiene rol
    public function hasRole(string $role) : bool
    {
        return $this->roles->contains('name', $role);
    }

    // Agrega rol
    public function assignRole(string $role)
    {
        $role = Role::firstOrCreate(['name' => $role]);
        $this->roles()->attach($role);
    }

    // Eliminar rol
    public function removeRoll(string $role)
    {
        $role = Role::where('name', $role)->first();
        $this->roles()->detach($role);
    }


    // Relacion con vehiculos
    public function vehiclesInUse()
    {
        return $this->hasMany(Vehicle::class);
    }

    // relacion con direcciones
    public function addresses() : HasMany
    {
        return $this->hasMany(Address::class);
    }
}
