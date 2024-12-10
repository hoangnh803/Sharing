<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Quan hệ với Documents
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Quan hệ với Subjects qua bảng class_subjects
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects', 'class_id', 'subject_id');
    }
}

