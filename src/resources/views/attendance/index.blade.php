@extends('layouts.app')

@section('title','トップページ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/index.css') }}">
@endsection

@section('body_class', 'bg-attendance')

@section('content')
<div class="attendance-form">
  <div class="attendance-form__content">
    <div class="attendance-form__status">
      @if ($status === '勤務外')
        勤務外
      @elseif ($status === '出勤中')
        出勤中
      @elseif ($status === '退勤済')
        退勤済
      @endif
    </div>

    <div class="attendance-form__date" id="current-date">{{ $date }}</div>
    <div class="attendance-form__time" id="current-time">{{ $time }}</div>

    <div class="attendance-form__button">
      @if ($status === '勤務外')
        <form action="{{ route('attendance.start') }}" method="post">
          @csrf
          <button class="attendance-form__button-submit" type="submit">出勤</button>
        </form>
      @elseif ($status === '出勤中')
        <form action="{{ route('attendance.end') }}" method="post">
          @csrf
          <button class="attendance-form__button-submit" type="submit">退勤</button>
        </form>
        <button class="attendance-form__button-submit" disabled>休憩入</button>
      @elseif ($status === '退勤済')
        <p>お疲れ様でした。</p>
      @endif
    </div>
  </div>
</div>

<script>
function updateTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const currentTime = `${hours}:${minutes}`;
    document.getElementById('current-time').textContent = currentTime;
  }

  updateTime();

  setInterval(updateTime, 1000);
</script>
@endsection