<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportReason extends Model
{
    use HasFactory;

    protected $fillable = ['reason'];

    // Quan hệ với Reports
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
