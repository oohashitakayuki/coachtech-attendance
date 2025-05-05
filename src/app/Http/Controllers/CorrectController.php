<?php

namespace App\Http\Controllers;

use App\Models\Correct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CorrectController extends Controller
{
    public function showRequestList(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'awaiting-approval');

        if ($tab === 'awaiting-approval') {
            $requests = Correct::with('attendance.user')
                ->whereHas('attendance', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->get();
        } else {
            $requests = collect();
        }

        return view('attendance.request', compact('tab', 'requests'));
    }

    public function sendCorrectRequest()
    {

    }
}
