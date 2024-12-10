<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Quan hệ với Universities qua bảng university_subjects
    public function universities()
    {
        return $this->belongsToMany(University::class, 'university_subjects');
    }

    // Quan hệ với Classes qua bảng class_subjects
    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_subjects');
    }

    // Quan hệ với Documents
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}

