<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Correct;
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
            $lastRest = $attendance->rests()->latest()->first();
            if ($lastRest && $lastRest->break_start && !$lastRest->break_end) {
                $status = '休憩中';
            } else {
                $status = '出勤中';
            }
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

        if (!$attendance || !$attendance->work_start || $attendance->work_end) {
            return redirect()->route('attendance.index');
        }

        if ($attendance && !$attendance->work_end) {
            $attendance->update([
                'work_end' => $now->toTimeString(),
            ]);

            $start = Carbon::createFromFormat('H:i:s', $attendance->work_start);
            $end = Carbon::createFromFormat('H:i:s', $now->toTimeString());
            $workDuration = $start->diffInSeconds($end);

            $totalBreakSeconds = $attendance->rests->sum(function ($rest) {
                if ($rest->break_start && $rest->break_end) {
                    $start = Carbon::createFromFormat('H:i:s', $rest->break_start);
                    $end = Carbon::createFromFormat('H:i:s', $rest->break_end);
                    return $start->diffInSeconds($end);
                }
                return 0;
            });

            $netWorkSeconds = $workDuration - $totalBreakSeconds;
            $netWorkTime = gmdate('H:i:s', $netWorkSeconds);

            $attendance->update([
                'work_time' => $netWorkTime,
            ]);
        }

        session(['attendance_status' => '退勤済']);

        return redirect()->route('attendance.index');
    }

    public function showAttendanceList(Request $request)
    {
        $user = Auth::user();

        $currentMonth = $request->input('currentMonth', Carbon::now()->format('Y-m'));
        $displayMonth = Carbon::createFromFormat('Y-m', $currentMonth)->format('Y/m');

        $startDate = Carbon::createFromFormat('Y-m', $currentMonth)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $rawAttendances = Attendance::with(['correct', 'rests'])
            ->where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $grouped = $rawAttendances->groupBy('date')->map(function ($items) {
            return $items->first(function ($item) {
                return $item->correct && $item->correct->approved_at;
            }) ?? $items->first();
        });

        $attendances = $grouped->sortBy('date')->values();

        return view('attendance.list', compact('currentMonth', 'displayMonth', 'attendances'));
    }

    public function showAttendanceDetail($id)
    {
        $user = Auth::user();
        $attendance = Attendance::with('rests')->where('id',$id)->first();
        $correct = Correct::where('attendance_id', $attendance->id)->latest()->first();

        return view('attendance.show', compact('user', 'attendance', 'correct'));
    }
}
