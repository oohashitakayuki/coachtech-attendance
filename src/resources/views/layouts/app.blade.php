<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  @yield('css')
</head>

<body class="@yield('body_class')">
  <div class="app">
    <header class="header">
      <a href="/attendance"><img class="header__logo" src="{{ asset('img/logo.svg') }}" alt="ロゴ"></a>

      @php
        $attendanceStatus = session('attendance_status');
      @endphp

      @if (!in_array(Route::currentRouteName(), ['register', 'login']))
      <nav class="header-nav">
        <ul class="header-nav__menu">
          @if ($attendanceStatus === '退勤済')
            <li class="header-nav__item"><a class="header-nav__link" href="/attendance/list">今月の出勤一覧</a></li>
            <li class="header-nav__item"><a class="header-nav__link" href="/stamp_correction_request/list">申請一覧</a></li>
          @else
            <li class="header-nav__item"><a class="header-nav__link" href="/attendance">勤怠</a></li>
            <li class="header-nav__item"><a class="header-nav__link" href="/attendance/list">勤怠一覧</a></li>
            <li class="header-nav__item"><a class="header-nav__link" href="/stamp_correction_request/list">申請</a></li>
          @endif
          <form class="header-nav__button" action="/logout" method="post">
            @csrf
            <button onclick="location.href='{{ route('logout') }}'" class="header-nav__logout">ログアウト</button>
          </form>
        </ul>
      </nav>
      @endif
    </header>

    <main class="content">
      @yield('content')
    </main>
  </div>
</body>
</html>