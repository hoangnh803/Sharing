<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_id',
        'downloaded_at',
    ];
    
    public $timestamps = false; // Thêm dòng này để tắt tính năng timestamps

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với Document
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
