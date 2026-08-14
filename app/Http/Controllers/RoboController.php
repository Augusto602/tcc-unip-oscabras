<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoboController extends Controller
{
    public function index()
    {
        return view('robo.index');
    }
}     