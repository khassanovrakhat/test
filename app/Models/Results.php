<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Results extends Model
{
    use HasFactory;
    protected $table = "results";

    public function test(){
        return $this->belongsTo(Test::class);
    }
    public function answer(){
        return $this->hasMany(Answer::class);
    }
    public function student(){
        return $this->hasMany(Student::class);
    }
}
