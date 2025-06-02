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
      @elseif ($status === '休憩中')
        休憩中
      @elseif ($status === '退勤済')
        退勤済
      @endif
    </div>

    <div class="attendance-form__date" id="current-date">{{ $date }}</div>
    <div class="attendance-form__time" id="current-time">{{ $time }}</div>

    @if ($status === '勤務外')
    <form class="attendance-form__button" action="{{ route('attendance.start') }}" method="post">
      @csrf
      <button class="attendance-form__work-start" type="submit">出勤</button>
    </form>

    @elseif ($status === '出勤中')
    <div class="attendance-form__button-box">
      <form class="attendance-form__button" action="{{ route('attendance.end') }}" method="post">
        @csrf
        <button class="attendance-form__work-end" type="submit">退勤</button>
      </form>

      <form class="attendance-form__button" action="{{ route('rest.start') }}" method="post">
        @csrf
        <button class="attendance-form__break-start" type="submit">休憩入</button>
      </form>
    </div>

    @elseif ($status === '休憩中')
    <form class="attendance-form__button" action="{{ route('rest.end') }}" method="post">
      @csrf
      <button class="attendance-form__break-end" type="submit">休憩戻</button>
    </form>

    @elseif ($status === '退勤済')
      <p class="attendance-form__message">お疲れ様でした。</p>
    @endif
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