<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'document_id', 'report_reason_id', 'user_note'];

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

     // Quan hệ với ReportReason
     public function reportReason()
     {
         return $this->belongsTo(ReportReason::class, 'report_reason_id');
     }
}
