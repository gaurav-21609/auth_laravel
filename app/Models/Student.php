<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'age',
    ];

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}
