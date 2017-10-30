<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $dateitme = ['lastlogin_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'gender', 'avatar', 'lastlogin_at', 'email', 'password', 'cell',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Find the user for passport authentication
     * @param  string $identifier The passed in username cell/email
     * @return User
     */
    public function findForPassport($identifier) {
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'cell';
        return User::where($field, $identifier)->first();
    }
}
