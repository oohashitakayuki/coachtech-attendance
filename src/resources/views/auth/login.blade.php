@extends('layouts.app')

@section('title','ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('body_class', 'bg-auth')

@section('content')
<div class="login-form">
  <h2 class="login-form__heading">ログイン</h2>
  <form class="login-form__inner" action="/login" method="post" novalidate>
    @csrf
    <div class="login-form__group">
      <div class="login-form__group-content">
        <label class="login-form__label" for="email">メールアドレス</label>
        <input class="login-form__input" type="email" name="email" id="email" value="{{ old('email') }}">
      </div>
      <div class="login-form__error-message">
        @error('email')
        {{ $message }}
        @enderror
      </div>
    </div>
    <div class="login-form__group">
      <div class="login-form__group-content">
        <label class="login-form__label" for="password">パスワード</label>
        <input class="login-form__input" type="password" name="password" id="password">
      </div>
      <div class="login-form__error-message">
        @error('password')
        {{ $message }}
        @enderror
      </div>
    </div>
    <div class="login-form__button">
      <button class="login-form__button-submit submit-button" type="submit">ログインする</button>
    </div>
  </form>
  <div class="register__link">
    <a class="register__button-submit link-button" href="/register">会員登録はこちら</a>
  </div>
</div>
@endsection