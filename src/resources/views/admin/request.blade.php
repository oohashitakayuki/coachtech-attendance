@extends('layouts.app')

@section('title','申請一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/request.css') }}">
@endsection

@section('body_class', 'bg-admin')

@section('content')
<div class="request-list">
  <h2 class="request-list__heading">申請一覧</h2>
  <div class="request-list__content">
    <div class="request-tab">
      <a href="{{ route('admin.showRequestList') }}" class="request-tab__awaiting-approval tab-switch {{ $tab === 'awaiting-approval' ? 'active' : '' }}">承認待ち</a>
      <a href="{{ route('admin.showRequestList', ['tab' => 'approved-conformed']) }}" class="request-tab__approved-conformed tab-switch {{ $tab === 'approved-conformed' ? 'active' : '' }}">承認済み</a>
    </div>
    <table class="request-list__table">
      <tr class="request-data">
        <th class="request-data__label">状態</th>
        <th class="request-data__label">名前</th>
        <th class="request-data__label">対象日時</th>
        <th class="request-data__label">申請理由</th>
        <th class="request-data__label">申請日時</th>
        <th class="request-data__label">詳細</th>
      </tr>
      @foreach ($corrects as $correct)
      <tr class="request-data">
        <td class="request-data__status">
          @if ($tab === 'awaiting-approval')
            <span class="request-status__awaiting-approval">承認待ち</span>
          @elseif ($tab === 'approved-conformed')
            <span class="request-status__approved-conformed">承認済み</span>
          @endif
        </td>
        <td class="request-data__user-name">{{ $correct->attendance->user->name }}</td>
        <td class="request-data__date">{{ \Carbon\Carbon::parse($correct->attendance->date)->format('Y/m/d') }}</td>
        <td class="request-data__comment">{{ $correct->comment }}</td>
        <td class="request-data__date">{{ \Carbon\Carbon::parse($correct->created_at)->format('Y/m/d') }}</td>
        <td class="request-data__detail"><a href="{{ route('admin.showRequestDetail', ['attendance_correct_request' => $correct->id]) }}" class="request-data__detail-link">詳細</a></td>
      </tr>
      @endforeach
    </table>
  </div>
</div>
@endsection