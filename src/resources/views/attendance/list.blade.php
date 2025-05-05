@extends('layouts.app')

@section('title','勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/list.css') }}">
@endsection

@section('body_class', 'bg-attendance')

@section('content')
<div class="attendance-list">
  <h2 class="attendance-list__heading">勤怠一覧</h2>
  <div class="attendance-list__month-navigation">
    @php
      $current = \Carbon\Carbon::createFromFormat('Y-m', $yearMonth);
      $prevMonth = $current->copy()->subMonth()->format('Y-m');
      $nextMonth = $current->copy()->addMonth()->format('Y-m');
    @endphp

    <a href="{{ route('attendance.showAttendanceList', ['yearMonth' => $prevMonth]) }}" class="arrow">←</a>
    <a href="{{ route('attendance.showAttendanceList', ['yearMonth' => $prevMonth]) }}" class="attendance-list__nav-link">前月</a>

    <span class="attendance-list__current-month">{{ $displayMonth }}</span>

    <a href="{{ route('attendance.showAttendanceList', ['yearMonth' => $nextMonth]) }}" class="arrow">翌月</a>
    <a href="{{ route('attendance.showAttendanceList', ['yearMonth' => $nextMonth]) }}" class="attendance-list__nav-link">→</a>
  </div>

  <table class="attendance-list__table">
    <tr>
      <th>日付</th>
      <th>出勤</th>
      <th>退勤</th>
      <th>休憩</th>
      <th>合計</th>
      <th>詳細</th>
    </tr>
    @foreach ($attendances as $attendance)
    <tr>
      <td>{{ \Carbon\Carbon::parse($attendance->date)->format('m/d') }}({{ ['日','月','火','水','木','金','土'][\Carbon\Carbon::parse($attendance->date)->dayOfWeek] }})</td>
      <td>{{ \Carbon\Carbon::parse($attendance->work_start)->format('H:i') }}</td>
      <td>{{ \Carbon\Carbon::parse($attendance->work_end)->format('H:i') }}</td>
      <td>{{ optional($attendance->rests->first())->break_time ? \Carbon\Carbon::parse(optional($attendance->rests->first())->break_time)->format('H:i') : '0:00' }}</td>
      <td>{{ \Carbon\Carbon::parse($attendance->work_time)->format('H:i') }}</td>
      <td><a href="{{ route('attendance.editAttendanceDetail', ['id' => $attendance->id]) }}">詳細</a></td>
    </tr>
    @endforeach
  </table>
</div>
@endsection