<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $table = "questions";

    public function test(){
        return $this->belongsTo(Test::class);
    }
    public function answer(){
        return $this->hasMany(Answer::class);
    }
    public function question(){
        return $this->hasMany(Question::class);
    }
    public function student(){
        return $this->hasMany(Student::class);
    }
}
