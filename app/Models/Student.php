<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Authenticatable implements JWTSubject
{
    use Notifiable, HasFactory;

    protected $table = "students";

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function schools(){
        return $this->belongsTo(School::class);
    }

    public function tests(){
        return $this->belongsToMany(Test::class);
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
