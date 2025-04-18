<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now()->locale('ja');
        $date = $now->isoFormat('YYYY年M月D日(ddd)');
        $time = $now->format('H:i');

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();

        $status = '勤務外';

        if ($attendance && $attendance->work_start && !$attendance->work_end) {
            $status = '出勤中';
        } elseif ($attendance && $attendance->work_end) {
            $status = '退勤済';
        }

        session(['attendance_status' => $status]);

        return view('attendance.index', compact('date', 'time', 'status'));
    }

    public function workStart(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();

        if (!$attendance) {
            Attendance::create([
                'user_id' => $user->id,
                'date' => $now->toDateString(),
                'work_start' => $now->toTimeString(),
            ]);
        }

        return redirect()->route('attendance.index');
    }

    public function workEnd(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();

        if ($attendance && !$attendance->work_end) {
            $attendance->update([
                'work_end' => $now->toTimeString(),
            ]);
        }

        return redirect()->route('attendance.index');
    }

    public function attendance()
    {
        return view('attendance.list');
    }
}
