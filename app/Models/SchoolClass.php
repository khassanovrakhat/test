<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    public function students(){
        return $this->hasMany(Student::class);
    }
    public function teahcers(){
        return $this->belongsTo(Teacher::class);
    }
    use HasFactory;
}
