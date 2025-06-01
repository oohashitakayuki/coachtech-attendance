<?php

namespace App\Http\Controllers;

use App\Http\Requests\CorrectRequest;
use App\Models\Attendance;
use App\Models\Correct;
use App\Models\Rest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CorrectController extends Controller
{
    public function showRequestList(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'awaiting-approval');

        $corrects = Correct::with('attendance.user')
            ->whereHas('attendance', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });

        if ($tab === 'awaiting-approval') {
            $corrects->whereNull('approved_at');
        } elseif ($tab === 'approved-conformed') {
            $corrects->whereNotNull('approved_at');
        }

        $corrects = $corrects->orderByDesc('created_at')->get();

        return view('attendance.request', compact('user', 'tab', 'corrects'));
    }

    public function storeCorrectRequest(CorrectRequest $request)
    {
        $user = Auth::user();

        $workStart = $request->input('work_start');
        $workEnd = $request->input('work_end');
        $comment = $request->input('comment');
        $breakStarts = $request->input('break_start');
        $breakEnds = $request->input('break_end');

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => $request->input('date'),
            'work_start' => $workStart,
            'work_end' => $workEnd,
        ]);

        foreach ($breakStarts as $index => $start) {
            $end = $breakEnds[$index] ?? null;

            if ($start && $end) {
                $startTime = Carbon::createFromFormat('H:i', $start);
                $endTime = Carbon::createFromFormat('H:i', $end);
                $breakTime = $startTime->diff($endTime)->format('%H:%I:%S');

                Rest::create([
                    'attendance_id' => $attendance->id,
                    'break_start' => $start,
                    'break_end' => $end,
                    'break_time' => $breakTime,
                ]);
            }
        }

        $start = Carbon::createFromFormat('H:i', $workStart);
        $end = Carbon::createFromFormat('H:i', $workEnd);
        $workSeconds = $start->diffInSeconds($end);

        $restSeconds = $attendance->rests->sum(function ($rest) {
            return Carbon::parse($rest->break_start)
                ->diffInSeconds(Carbon::parse($rest->break_end));
        });

        $netWorkSeconds = $workSeconds - $restSeconds;
        $attendance->update([
            'work_time' => gmdate('H:i:s', $netWorkSeconds),
        ]);

        Correct::create([
            'attendance_id' => $attendance->id,
            'comment' => $comment,
        ]);

        return redirect()->route('attendance.showAttendanceDetail', ['id' => $attendance->id]);
    }
}
