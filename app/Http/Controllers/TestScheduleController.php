<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class TestScheduleController extends Controller
{
    public function index()
{
    $schedule = Schedule::first();
    return view('test_schedule', compact('schedule'));
}
}
