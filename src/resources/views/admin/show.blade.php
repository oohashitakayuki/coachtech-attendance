@extends('layouts.app')

@section('title','勤怠詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/show.css') }}">
@endsection

@section('body_class', 'bg-admin')

@section('content')
<div class="attendance-detail">
  <h2 class="attendance-detail__heading">勤怠詳細</h2>
  <form class="attendance-detail__inner" action="{{ route('admin.storeAttendanceCorrect', ['id' => $attendance->id]) }}" method="post">
    <input type="hidden" name="date" value="{{ $attendance->date }}">
    @csrf
    <div class="attendance-detail__content">
      <div class="attendance-detail__group">
        <label class="attendance-detail__label">名前</label>
        <p class="attendance-detail__user-name">{{ $attendance->user->name }}</p>
      </div>

      <div class="attendance-detail__group">
        <label class="attendance-detail__label">日付</label>
        <p class="attendance-detail__date">{{ \Carbon\Carbon::parse($attendance->date)->format('Y年') }}</p>
        <p class="attendance-detail__date">{{ \Carbon\Carbon::parse($attendance->date)->format('n月j日') }}</p>
      </div>

      <div class="attendance-detail__group">
        <label class="attendance-detail__label">出勤・退勤</label>
        <div class="attendance-detail__work-time">
          <input class="attendance-detail__input" type="text" name="work_start" id="work_start" value="{{ \Carbon\Carbon::parse($attendance->work_start)->format('H:i') }}">
          <span class="time-range__tilde">〜</span>
          <input class="attendance-detail__input" type="text" name="work_end" id="work_end" value="{{ \Carbon\Carbon::parse($attendance->work_end)->format('H:i') }}">
          <div class="attendance-detail__error-message">
            @error('work_start')
            {{ $message }}
            @enderror
          </div>
          <div class="attendance-detail__error-message">
            @error('work_end')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>

      @foreach ($attendance->rests as $index => $rest)
      <div class="attendance-detail__group">
        <label class="attendance-detail__label">{{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}</label>
        <div class="attendance-detail__break-time">
          <input class="attendance-detail__input" type="text" name="break_start[]" value="{{ \Carbon\Carbon::parse($rest->break_start)->format('H:i') }}">
          <span class="time-range__tilde">〜</span>
          <input class="attendance-detail__input" type="text" name="break_end[]" value="{{ \Carbon\Carbon::parse($rest->break_end)->format('H:i') }}">
          <div class="attendance-detail__error-message">
            @error("break_start.$index")
            {{ $message }}
            @enderror
          </div>
          <div class="attendance-detail__error-message">
            @error("break_end.$index")
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>
      @endforeach

      <div class="attendance-detail__group">
        <label class="attendance-detail__label">休憩{{ count($attendance->rests) + 1 }}</label>
        <div class="attendance-detail__new-break">
          <input class="attendance-detail__input" type="text" name="break_start[]" value="">
          <span class="time-range__tilde">〜</span>
          <input class="attendance-detail__input" type="text" name="break_end[]" value="">
          <div class="attendance-detail__error-message">
            @error("break_start." . count($attendance->rests))
            {{ $message }}
            @enderror
          </div>
          <div class="attendance-detail__error-message">
            @error("break_end." . count($attendance->rests))
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>

      <div class="attendance-detail__group">
        <label class="attendance-detail__label">備考</label>
        <div class="attendance-detail__comment-field">
          <textarea class="attendance-detail__textarea" name="comment" id="comment" cols="45" rows="4"></textarea>
          <div class="attendance-detail__error-message">
            @error('comment')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>
    </div>

    <div class="attendance-detail__button">
      <button class="attendance-detail__button-submit submit-button" type="submit">修正</button>
    </div>
  </form>
</div>
@endsection