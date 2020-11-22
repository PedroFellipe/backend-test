<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

// JWT contract
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject {
    use Notifiable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_picture_id','name', 'email', 'password','bio', 'city', 'state'
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    public function friendsThisUserAsked()
    {
        return $this->belongsToMany(User::class, 'friendship', 'first_user_id', 'second_user_id')
            ->withPivotValue('confirmed', 1)->withPivot('id');
    }

    public function friendsThisUserWasAsked()
    {
        return $this->belongsToMany(User::class, 'friendship', 'second_user_id', 'first_user_id')
            ->withPivotValue('confirmed', 1)->withPivot('id');
    }

    public function profile_picture()
    {
        return $this->belongsTo(Attachment::class, 'profile_picture_id');
    }
}
