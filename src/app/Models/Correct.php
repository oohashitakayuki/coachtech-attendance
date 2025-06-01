<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correct extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id', 'comment', 'approved_at'
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
