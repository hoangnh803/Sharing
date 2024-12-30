<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'preview',
        'type',
        'uploader_id',
        'university_id',
        'class_id',
        'subject_id',
        'document_type_id',
        'is_approved',
        'report_count',
    ];

    // Quan hệ với User (Người tải lên)
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    // Quan hệ với University
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    // Quan hệ với Class
    public function class()
    {
        return $this->belongsTo(Classes::class); // Đổi tên class nếu cần
    }

    // Quan hệ với Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Quan hệ với DocumentType
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
    // Quan hệ với Like
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Quan hệ với Download
    public function downloads()
    {
        return $this->hasMany(Download::class);
    }
    // Quan hệ với Report
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}

