<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $table = "schools";

    public function students(){
        return $this->hasMany(Student::class);
    }

    public function teahcers(){
        return $this->hasMany(Teacher::class);
    }
}
