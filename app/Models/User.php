<?php

namespace App\Models;

use App\Models\Contact;
use App\Models\UserIdentification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait;
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
    * @return random String
    */
    public static function generateVerificationCode() 
    {
        return Str::random(40);
    }

    /**
    * @return relationship. User has can upload many identification verification document
    */
    public function userIdentification()
    {
        return $this->hasMany(UserIdentification::class);
    }

    /**
    * @return relationship. A user hasMany contacts 
    */
    public function contact()
    {
        return $this->hasMany(Contact::class);
    }
}
