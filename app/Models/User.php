<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Exceptions\FailduringCreate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    static function createNew($userData)
    {
        try {
            $newUser = new User();
            $newUser->name = $userData["name"];
            $newUser->email = $userData["email"];
            $newUser->street = $userData["street"];
            $newUser->postal = $userData["postal"];
            $newUser->city = $userData["city"];
            $newUser->password = Hash::make($userData["password"]);

            if ($newUser->save()) {
                return ["success" => true, "user" => $newUser];
            } else {
                throw new FailduringCreate("error al crear el usuario");
            }
        } catch (\Exception $e) {
            return ["success" => true, "error" => $e];
        }
    }
}
