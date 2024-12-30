<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',         // Thêm type vào đây
        'data',         // Thêm data vào đây
        'read_at',
    ];

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ polymorphic với notifiable (User hoặc bất kỳ mô hình nào khác)
    public function notifiable()
    {
        return $this->morphTo();
    }
}


