<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Authenticatable implements JWTSubject
{
    use Notifiable, HasFactory;

    protected $table = "teachers";

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function tests(){
        return $this->hasMany(Test::class);
    }


    public function schools(){
        return $this->belongsTo(School::class);
    }

    public function schoolclasses(){
        return $this->hasMany(SchoolClass::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
