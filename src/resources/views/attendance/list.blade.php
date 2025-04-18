@extends('layouts.app')

@section('title','勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/list.css') }}">
@endsection

@section('body_class', 'bg-attendance')

@section('content')
<div class="attendance-list"></div>
@endsection