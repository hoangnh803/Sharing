<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Quan hệ với Documents
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Quan hệ với Subjects qua bảng university_subjects
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'university_subjects');
    }
}
