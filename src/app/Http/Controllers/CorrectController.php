<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CorrectController extends Controller
{
    public function showRequestList()
    {
        return view('attendance.request');
    }

    public function sendCorrectRequest()
    {

    }
}
