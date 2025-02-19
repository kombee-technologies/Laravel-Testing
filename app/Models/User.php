<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles,HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    



     protected $fillable = [
        'first_name', 'last_name', 'email', 'contact_number', 'postcode',
        'password', 'gender', 'state_id', 'city_id', 'hobbies', 'uploaded_files'
    ];

    protected $casts = [
        'roles' => 'array',
        'hobbies' => 'array',
        'uploaded_files' => 'array',
    ];
 
     public function city() { return $this->belongsTo(City::class); }
     public function state() { return $this->belongsTo(State::class); }



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





    public function roles()
{
    return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
}

   



    public function assignRole($role)
    {
        $this->roles()->attach($role);
    }

    public function removeRole($role)
    {
        $this->roles()->detach($role);
    }
}
