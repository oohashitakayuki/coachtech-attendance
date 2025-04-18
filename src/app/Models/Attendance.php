<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'work_start', 'work_end', 'work_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
