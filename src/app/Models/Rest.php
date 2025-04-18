<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id', 'break_start', 'break_end', 'break_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
