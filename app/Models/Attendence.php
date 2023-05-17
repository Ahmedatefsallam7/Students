<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    use HasFactory;
    protected $fillable = [
        'joiner_id',
        'subject_id',
        'attend_code',
    ];
    
    public function joiners()
    {
        return $this->belongsTo(Joiner::class, 'joiner_id');
    }
    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
