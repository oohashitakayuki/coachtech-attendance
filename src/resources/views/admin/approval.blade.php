@extends('layouts.app')

@section('title','修正申請承認')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/approval.css') }}">
@endsection

@section('body_class', 'bg-admin')

@section('content')
<div class="approval-form">
  <h2 class="approval-form__heading">勤怠詳細</h2>
  <form class="approval-form__inner" action="{{ route('admin.storeCorrectApproval', ['attendance_correct_request' => $correct->id]) }}" method="post">
    @csrf
    <div class="approval-form__content">
      <div class="approval-form__group">
        <label class="approval-form__label">名前</label>
        <p class="approval-form__user-name">{{ $correct->attendance->user->name }}</p>
      </div>

      <div class="approval-form__group">
        <label class="approval-form__label">日付</label>
        <p class="approval-form__date">{{ \Carbon\Carbon::parse($correct->attendance->date)->format('Y年') }}</p>
        <p class="approval-form__date">{{ \Carbon\Carbon::parse($correct->attendance->date)->format('n月j日') }}</p>
      </div>

      <div class="approval-form__group">
        <label class="approval-form__label">出勤・退勤</label>
        <p class="approval-form__work-start">{{ \Carbon\Carbon::parse($correct->attendance->work_start)->format('H:i') }}</p>
        <span class="time-range__tilde">〜</span>
        <p class="approval-form__work-end">{{ \Carbon\Carbon::parse($correct->attendance->work_end)->format('H:i') }}</p>
      </div>

      @foreach ($attendance->rests as $index => $rest)
      <div class="approval-form__group">
        <label class="approval-form__label">{{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}</label>
        <p class="approval-form__break-start">{{ \Carbon\Carbon::parse($rest->break_start)->format('H:i') }}</p>
        <span class="time-range__tilde">〜</span>
        <p class="approval-form__break-end">{{ \Carbon\Carbon::parse($rest->break_end)->format('H:i') }}</p>
      </div>
      @endforeach

      <div class="approval-form__group">
        <label class="approval-form__label">備考</label>
        <p class="approval-form__comment">{{ $correct->comment }}</p>
      </div>
    </div>

    <div class="approval-form__button">
      @if (is_null($correct->approved_at))
        <button class="approval-form__button-submit submit-button" type="submit">承認</button>
      @else
        <button class="approval-form__button-submit submit-button" type="button" disabled>承認済み</button>
      @endif
    </div>
  </form>
</div>
@endsection