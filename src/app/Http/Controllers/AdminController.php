<?php

namespace App\Http\Controllers;

use App\Http\Requests\CorrectRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Attendance;
use App\Models\Correct;
use App\Models\Rest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function createLoginForm()
    {
        return view('admin.auth');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/admin/attendance/list');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        return redirect('/admin/login');
    }

    public function showAttendanceList(Request $request)
    {
        $currentDate = $request->input('currentDate', Carbon::now()->format('Y-m-d'));
        $carbonDate = Carbon::createFromFormat('Y-m-d', $currentDate)->locale('ja');
        $displayDate = $carbonDate->format('Y/m/d');
        $headingDate = $carbonDate->isoFormat('YYYY年M月D日');

        $rawAttendances = Attendance::with(['user', 'rests', 'correct'])
            ->where('date', $currentDate)
            ->get();

        $attendances = $rawAttendances->groupBy('user_id')->map(function ($items) {
            return $items->first(function ($item) {
                return $item->correct && $item->correct->approved_at;
            }) ?? $items->first();
        })->sortBy('user.name')->values();

        return view('admin.index', compact('currentDate', 'displayDate', 'headingDate', 'attendances'));
    }

    public function showStaffList()
    {
        $users = User::all();
        return view('admin.list', compact('users'));
    }

    public function showStaffAttendance(Request $request, $id)
    {
        $user = User::where('id',$id)->first();

        $currentMonth = $request->input('currentMonth', Carbon::now()->format('Y-m'));
        $displayMonth = Carbon::createFromFormat('Y-m', $currentMonth)->format('Y/m');

        $startDate = Carbon::createFromFormat('Y-m', $currentMonth)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $rawAttendances = Attendance::with(['rests', 'correct'])
            ->where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $attendances = $rawAttendances->groupBy('date')->map(function ($items) {
            return $items->first(function ($item) {
                return $item->correct && $item->correct->approved_at;
            }) ?? $items->first();
        })->sortBy('date')->values();

        return view('admin.attendance', compact('user', 'currentMonth', 'displayMonth', 'attendances'));
    }

    public function showAttendanceDetail($id)
    {
        $attendance = Attendance::with('rests')->where('id',$id)->first();
        $correct = Correct::where('attendance_id', $attendance->id)->latest()->first();

        return view('admin.show', compact('attendance', 'correct'));
    }

    public function storeAttendanceCorrect(CorrectRequest $request, $id)
    {
        $attendance = Attendance::with('rests')->where('id',$id)->first();

        $workStart = $request->input('work_start');
        $workEnd = $request->input('work_end');
        $comment = $request->input('comment');
        $breakStarts = $request->input('break_start');
        $breakEnds = $request->input('break_end');

        $attendance->update([
            'work_start' => $workStart,
            'work_end' => $workEnd,
        ]);

        foreach ($attendance->rests as $index => $rest) {
            if (!empty($breakStarts[$index]) && !empty($breakEnds[$index])) {
                $start = Carbon::createFromFormat('H:i', $breakStarts[$index]);
                $end = Carbon::createFromFormat('H:i', $breakEnds[$index]);
                $rest->update([
                    'break_start' => $start->format('H:i:s'),
                    'break_end' => $end->format('H:i:s'),
                    'break_time' => $start->diff($end)->format('%H:%I:%S'),
                ]);
            }
        }

        $index = count($attendance->rests);
        if (!empty($breakStarts[$index]) && !empty($breakEnds[$index])) {
            $start = Carbon::createFromFormat('H:i', $breakStarts[$index]);
            $end = Carbon::createFromFormat('H:i', $breakEnds[$index]);
            Rest::create([
                'attendance_id' => $attendance->id,
                'break_start' => $start->format('H:i:s'),
                'break_end' => $end->format('H:i:s'),
                'break_time' => $start->diff($end)->format('%H:%I:%S'),
            ]);
        }

        $start = Carbon::createFromFormat('H:i', $workStart);
        $end = Carbon::createFromFormat('H:i', $workEnd);
        $workSeconds = $start->diffInSeconds($end);

        $restSeconds = $attendance->rests()->get()->sum(function ($rest) {
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
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.showAttendanceList');
    }

    public function showRequestList(Request $request)
    {
        $tab = $request->query('tab', 'awaiting-approval');

        if ($tab === 'awaiting-approval') {
            $corrects = Correct::with(['attendance.user'])
                ->whereNull('approved_at')
                ->orderByDesc('created_at')
                ->get();
        } else {
            $corrects = Correct::with(['attendance.user'])
                ->whereNotNull('approved_at')
                ->orderByDesc('approved_at')
                ->get();
        }

        return view('admin.request', compact('tab', 'corrects'));
    }

    public function showRequestDetail($attendance_correct_request)
    {
        $correct = Correct::with(['attendance.user', 'attendance.rests'])->findOrFail($attendance_correct_request);
        $attendance = $correct->attendance;

        return view('admin.approval', compact('correct', 'attendance'));
    }

    public function storeCorrectApproval($attendance_correct_request)
    {
        $correct = Correct::findOrFail($attendance_correct_request);

        if (is_null($correct->approved_at)) {
            $correct->approved_at = Carbon::now();
            $correct->save();
        }

        return redirect()->route('admin.showRequestDetail', ['attendance_correct_request' => $attendance_correct_request]);
    }
}
