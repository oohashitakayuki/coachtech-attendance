@extends('layouts.app')

@section('title','申請一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/request.css') }}">
@endsection

@section('body_class', 'bg-attendance')

@section('content')
<div class="request-list"></div>
@endsection