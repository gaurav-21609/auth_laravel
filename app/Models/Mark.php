<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $fillable = ['student_id', 'subject', 'score', 'grade'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
