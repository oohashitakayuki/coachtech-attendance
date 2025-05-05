@extends('layouts.app')

@section('title','勤怠詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/show.css') }}">
@endsection

@section('body_class', 'bg-attendance')

@section('content')
<div class="attendance-detail">
  <h2 class="attendance-detail__heading">勤怠詳細</h2>
  <form class="attendance-detail__inner" action="" method="post">
    @csrf
    <div class="attendance-detail__content">
      <div class="attendance-detail__group">
        <label>名前</label>
        <p>{{ $user->name }}</p>
      </div>
      <div class="attendance-detail__group">
        <label>日付</label>
        <p>{{ \Carbon\Carbon::parse($attendance->date)->format('Y年') }}</p>
        <p>{{ \Carbon\Carbon::parse($attendance->date)->format('n月j日') }}</p>
      </div>
      <div class="attendance-detail__group">
        <label>出勤・退勤</label>
        <input type="text" value="{{ \Carbon\Carbon::parse($attendance->work_start)->format('H:i') }}">
        <span>〜</span>
        <input type="text" value="{{ \Carbon\Carbon::parse($attendance->work_end)->format('H:i') }}">
      </div>
      <div class="attendance-detail__group">
        <label>休憩</label>
        <input type="text" value="{{ optional($attendance->rests->first())->break_start ? \Carbon\Carbon::parse(optional($attendance->rests->first())->break_start)->format('H:i') : '' }}">
        <span>〜</span>
        <input type="text" value="{{ optional($attendance->rests->first())->break_end ? \Carbon\Carbon::parse(optional($attendance->rests->first())->break_end)->format('H:i') : '' }}">
      </div>
      <div class="attendance-detail__group">
        <label>備考</label>
        <td><textarea name="" id="">{{ $correct->comment ?? '' }}</textarea></td>
      </div>
    </div>

    <div class="attendance-detail__button">
      <button class="attendance-detail__button-submit">修正</button>
    </div>
  </form>
</div>
@endsection