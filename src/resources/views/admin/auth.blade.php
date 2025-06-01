@extends('layouts.app')

@section('title','管理者ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/auth.css') }}">
@endsection

@section('body_class', 'bg-auth')

@section('content')
<div class="auth-form">
  <h2 class="auth-form__heading">管理者ログイン</h2>
  <form class="auth-form__inner" action="/admin/login" method="post" novalidate>
    @csrf
    <div class="auth-form__group">
      <div class="auth-form__group-content">
        <label class="auth-form__label" for="email">メールアドレス</label>
        <input class="auth-form__input" type="email" name="email" id="email" value="{{ old('email') }}">
      </div>
      <div class="auth-form__error-message">
        @error('email')
        {{ $message }}
        @enderror
      </div>
    </div>
    <div class="auth-form__group">
      <div class="auth-form__group-content">
        <label class="auth-form__label" for="password">パスワード</label>
        <input class="auth-form__input" type="password" name="password" id="password">
      </div>
      <div class="auth-form__error-message">
        @error('password')
        {{ $message }}
        @enderror
      </div>
    </div>
    <div class="auth-form__button">
      <button class="auth-form__button-submit submit-button" type="submit">管理者ログインする</button>
    </div>
  </form>
</div>
@endsection