<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $table = "tests";

    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function students(){
        return $this->belongsToMany(Student::class)->withTimestamps()->withPivot(['begin_time', 'end_time', 'size']);
    }

    public function questions(){
        return $this->hasMany(Question::class);
    }
}
