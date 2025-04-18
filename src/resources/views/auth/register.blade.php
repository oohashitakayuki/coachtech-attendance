@extends('layouts.app')

@section('title','会員登録')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('body_class', 'bg-auth')

@section('content')
<div class="register-form">
  <h2 class="register-form__heading">会員登録</h2>
  <form class="register-form__inner" action="/register" method="post" novalidate>
    @csrf
    <div class="register-form__group">
      <div class="register-form__group-content">
        <label class="register-form__label" for="name">ユーザー名</label>
        <input class="register-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
      </div>
      <div class="register-form__error-message">
        @error('name')
        {{ $message }}
        @enderror
      </div>
    </div>
    <div class="register-form__group">
      <div class="register-form__group-content">
        <label class="register-form__label" for="email">メールアドレス</label>
        <input class="register-form__input" type="email" name="email" id="email" value="{{ old('email') }}">
      </div>
      <div class="register-form__error-message">
        @error('email')
        {{ $message }}
        @enderror
      </div>
    </div>
    <div class="register-form__group">
      <div class="register-form__group-content">
        <label class="register-form__label" for="password">パスワード</label>
        <input class="register-form__input" type="password" name="password" id="password">
      </div>
      <div class="register-form__error-message">
        @error('password')
        {{ $message }}
        @enderror
      </div>
    </div>
    <div class="register-form__group">
      <div class="register-form__group-content">
        <label class="register-form__label" for="password_confirmation">確認用パスワード</label>
        <input class="register-form__input" type="password" name="password_confirmation" id="password_confirmation">
      </div>
      <div class="register-form__error-message">
        @error('password_confirmation')
        {{ $message }}
        @enderror
      </div>
    </div>
    <div class="register-form__button">
      <button class="register-form__button-submit submit-button" type="submit">登録する</button>
    </div>
  </form>
  <div class="login__link">
    <a class="login__button-submit link-button" href="/login">ログインはこちら</a>
  </div>
</div>
@endsection