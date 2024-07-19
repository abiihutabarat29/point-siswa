<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Rombel;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Tapel;
use App\Models\User;
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
        if (Auth::user()->role_id == 1) {
            $tapel = Tapel::where('status', 1)->first();
            $semester = Semester::where('status', 1)->first();
            $rombel = Rombel::where('tapel_id', $tapel->id)->where('semester_id', $semester->id)->count();
            $guru = Guru::count();
            $siswa = Siswa::count();
            $user = User::count();
            return view('dashboard.admin', compact('menu', 'rombel', 'guru', 'siswa', 'user'));
        } else {
            return view('dashboard.user', compact('menu'));
        }
    }
}
