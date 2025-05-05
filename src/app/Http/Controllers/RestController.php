<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Rest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestController extends Controller
{
    public function breakStart(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();

        if (!$attendance || $attendance->work_end) {
            return redirect()->route('attendance.index');
        }

        Rest::create([
            'attendance_id' => $attendance->id,
            'break_start' => $now->toTimeString(),
        ]);

        session(['attendance_status' => '休憩中']);

        return redirect()->route('attendance.index');
    }

    public function breakEnd(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();

        if (!$attendance || $attendance->work_end) {
            return redirect()->route('attendance.index');
        }

        $lastRest = $attendance->rests()
            ->whereNotNull('break_start')
            ->whereNull('break_end')
            ->latest()
            ->first();

        if ($lastRest) {
            $lastRest->update([
                'break_end' => $now->toTimeString(),
            ]);

            $start = Carbon::createFromFormat('H:i:s', $lastRest->break_start);
            $end = Carbon::createFromFormat('H:i:s', $now->toTimeString());
            $breakTime = $start->diff($end)->format('%H:%I:%S');

            $lastRest->update([
                'break_time' => $breakTime,
            ]);
        }

        session(['attendance_status' => '出勤中']);

        return redirect()->route('attendance.index');
    }
}
