<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class examController extends Controller
{
    public function index(){
        return view('testing.index');
    }
    public function exam(){
        return view('testing.exam');
    }
}
