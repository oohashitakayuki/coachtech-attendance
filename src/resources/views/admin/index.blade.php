@extends('layouts.app')

@section('title','勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/index.css') }}">
@endsection

@section('body_class', 'bg-admin')

@section('content')
<div class="attendance-list">
  <h2 class="attendance-list__heading">{{ $headingDate }}の勤怠</h2>
  <div class="attendance-list__date">
    @php
      $current = \Carbon\Carbon::createFromFormat('Y-m-d', $currentDate);
      $prevDate = $current->copy()->subDay()->format('Y-m-d');
      $nextDate = $current->copy()->addDay()->format('Y-m-d');
    @endphp

    <div class="attendance-list__previous-day">
      <a href="{{ route('admin.showAttendanceList', ['currentDate' => $prevDate]) }}"><i class="fa-solid fa-arrow-left"></i></a>
      <a href="{{ route('admin.showAttendanceList', ['currentDate' => $prevDate]) }}" class="attendance-list__nav-link">前日</a>
    </div>

    <div class="attendance-list__today">
      <i class="fa-regular fa-calendar-days"></i>
      <span class="attendance-list__current-date">{{ $displayDate }}</span>
    </div>

    <div class="attendance-list__next-day">
      <a href="{{ route('admin.showAttendanceList', ['currentDate' => $nextDate]) }}" class="attendance-list__nav-link">翌日</a>
      <a href="{{ route('admin.showAttendanceList', ['currentDate' => $nextDate]) }}"><i class="fa-solid fa-arrow-right"></i></a>
    </div>
  </div>

  <table class="attendance-list__table">
    <tr class="attendance-data">
      <th class="attendance-data__label">名前</th>
      <th class="attendance-data__label">出勤</th>
      <th class="attendance-data__label">退勤</th>
      <th class="attendance-data__label">休憩</th>
      <th class="attendance-data__label">合計</th>
      <th class="attendance-data__label">詳細</th>
    </tr>
    @foreach ($attendances as $attendance)
    <tr class="attendance-data">
      <td class="attendance-data__date">{{ $attendance->user->name }}</td>
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