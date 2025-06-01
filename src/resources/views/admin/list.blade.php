@extends('layouts.app')

@section('title','スタッフ一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/list.css') }}">
@endsection

@section('body_class', 'bg-admin')

@section('content')
<div class="staff-list">
  <h2 class="staff-list__heading">スタッフ一覧</h2>
  <table class="staff-list__table">
    <tr class="staff-data">
      <th class="staff-data__label">名前</th>
      <th class="staff-data__label">メールアドレス</th>
      <th class="staff-data__label">月次勤怠</th>
    </tr>
    @foreach($users as $user)
    <tr class="staff-data">
      <td class="staff-data__user-name">{{ $user->name }}</td>
      <td class="staff-data__email-address">{{ $user->email }}</td>
      <td class="staff-data__detail"><a href="{{ route('admin.showStaffAttendance', ['id' => $user->id, 'currentMonth' => now()->format('Y-m')]) }}" class="staff-data__detail-link">詳細</a></td>
    </tr>
    @endforeach
  </table>
</div>
@endsection