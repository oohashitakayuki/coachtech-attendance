@extends('layouts.app')

@section('title','スタッフ勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance.css') }}">
@endsection

@section('body_class', 'bg-admin')

@section('content')
<div class="staff-attendance">
  <h2 class="staff-attendance__heading">{{ $user->name }}さんの勤怠</h2>
  <div class="staff-attendance__month">
    @php
      $current = \Carbon\Carbon::createFromFormat('Y-m', $currentMonth);
      $prevMonth = $current->copy()->subMonth()->format('Y-m');
      $nextMonth = $current->copy()->addMonth()->format('Y-m');
    @endphp

    <div class="staff-attendance__last-month">
      <a href="{{ route('admin.showStaffAttendance', ['id' => $user->id, 'currentMonth' => $prevMonth]) }}"><i class="fa-solid fa-arrow-left"></i></a>
      <a href="{{ route('admin.showStaffAttendance', ['id' => $user->id, 'currentMonth' => $prevMonth]) }}" class="staff-attendance__nav-link">前月</a>
    </div>

    <div class="staff-attendance__this-month">
      <i class="fa-regular fa-calendar-days"></i>
      <span class="staff-attendance__current-month">{{ $displayMonth }}</span>
    </div>

    <div class="staff-attendance__next-month">
      <a href="{{ route('admin.showStaffAttendance', ['id' => $user->id, 'currentMonth' => $nextMonth]) }}"  class="staff-attendance__nav-link">翌月</a>
      <a href="{{ route('admin.showStaffAttendance', ['id' => $user->id, 'currentMonth' => $nextMonth]) }}"><i class="fa-solid fa-arrow-right"></i></a>
    </div>
  </div>

  <table class="staff-attendance__table">
    <tr class="attendance-data">
      <th class="attendance-data__label">日付</th>
      <th class="attendance-data__label">出勤</th>
      <th class="attendance-data__label">退勤</th>
      <th class="attendance-data__label">休憩</th>
      <th class="attendance-data__label">合計</th>
      <th class="attendance-data__label">詳細</th>
    </tr>
    @foreach ($attendances as $attendance)
    <tr class="attendance-data">
      <td class="attendance-data__date">{{ \Carbon\Carbon::parse($attendance->date)->format('m/d') }}({{ ['日','月','火','水','木','金','土'][\Carbon\Carbon::parse($attendance->date)->dayOfWeek] }})</td>
      <td class="attendance-data__time">{{ \Carbon\Carbon::parse($attendance->work_start)->format('H:i') }}</td>
      <td class="attendance-data__time">{{ \Carbon\Carbon::parse($attendance->work_end)->format('H:i') }}</td>
      <td class="attendance-data__time">
      @php
        $totalBreakSeconds = $attendance->rests->sum(function ($rest) {
          if ($rest->break_start && $rest->break_end) {
            $start = \Carbon\Carbon::createFromFormat('H:i:s', $rest->break_start);
            $end = \Carbon\Carbon::createFromFormat('H:i:s', $rest->break_end);
            return $start->diffInSeconds($end);
          }
          return 0;
        });
        echo gmdate('H:i', $totalBreakSeconds);
      @endphp
      </td>
      <td class="attendance-data__time">{{ \Carbon\Carbon::parse($attendance->work_time)->format('H:i') }}</td>
      <td class="attendance-data__detail"><a href="{{ route('admin.showAttendanceDetail', ['id' => $attendance->id]) }}" class="attendance-data__detail-link">詳細</a></td>
    </tr>
    @endforeach
  </table>
</div>
@endsection