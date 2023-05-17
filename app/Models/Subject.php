<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subjects';
    protected $fillable = ['sub_name', 'sub_code', 'creator_id', 'attend_code', 'take_attend'];

    function creator()
    {
        return $this->belongsTo(Creator::class, 'creator_id');
    }

    function joiners()
    {
        return  $this->belongsToMany(Joiner::class, 'subject_joiners');
    }

    function attendences()
    {
        return $this->hasMany(Attendence::class);
    }
}
