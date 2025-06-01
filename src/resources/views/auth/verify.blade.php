@extends('layouts.app')

@section('title','メール認証')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify.css') }}">
@endsection

@section('content')
<div class="verify-page">
  <div class="verify-page__inner">
    <div class="verify-page__message">
      <p>登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。</p>
    </div>
    <div class="verify-page__link">
      <button onclick="location.href='{{ route('attendance.index') }}'" class="verify-page__link-button">認証はこちらから</button>
    </div>
    <form class="verify-page__send-email" action="{{ route('verification.send') }}" method="post">
      @csrf
      <button class="send-email__button link-button" type="submit">認証メールを再送する</button>
    </form>
  </div>
</div>
@endsection