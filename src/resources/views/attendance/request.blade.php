@extends('layouts.app')

@section('title','申請一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/request.css') }}">
@endsection

@section('body_class', 'bg-attendance')

@section('content')
<div class="request-list">
  <h2 class="request-list__heading">申請一覧</h2>
  <div class="request-list__content">
    <div class="request-tab">
      <a href="{{ route('attendance.showRequestList') }}" class="request-tab__awaiting-approval tab-switch {{ $tab === 'awaiting-approval' ? 'active' : '' }}">承認待ち</a>
      <a href="{{ route('attendance.showRequestList', ['tab' => 'approved-conformed']) }}" class="request-tab__approved-conformed tab-switch {{ $tab === 'approved-conformed' ? 'active' : '' }}">承認済み</a>
    </div>
    <table class="request-list__table">
      <tr>
        <th>状態</th>
        <th>名前</th>
        <th>対象日時</th>
        <th>申請理由</th>
        <th>申請日時</th>
        <th>詳細</th>
      </tr>
      @forelse ($requests as $request)
      <tr>
        <td>{{ $tab === 'awaiting-approval' ? '承認待ち' : '' }}</td>
        <td>{{ $request->attendance->user->name }}</td>
        <td>{{ \Carbon\Carbon::parse($request->attendance->date)->format('Y/m/d') }}</td>
        <td>{{ $request->comment }}</td>
        <td>{{ \Carbon\Carbon::parse($request->created_at)->format('Y/m/d') }}</td>
        <td><a href="{{ route('attendance.editAttendanceDetail', ['id' => $request->attendance->id]) }}">詳細</a></td>
      </tr>
      @empty
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      @endforelse
    </table>
  </div>
</div>
@endsection