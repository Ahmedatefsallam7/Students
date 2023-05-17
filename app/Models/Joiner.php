<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Joiner extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'email', 'password'];

    function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_joiners');
    }
    function attendences()
    {
        return $this->hasMany(Attendence::class);
    }
}
