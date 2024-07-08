<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $menu = "Dashboard";

        if (Auth::user()->role_id = 1) {
            return view('dashboard.admin', compact('menu'));
        }
    }
}
