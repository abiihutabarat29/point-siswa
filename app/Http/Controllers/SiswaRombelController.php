<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\Siswa;
use App\Models\SiswaRombel;
use App\Models\Tapel;
use Illuminate\Http\Request;

class SiswaRombelController extends Controller
{
    public function index($id)
    {
        $tapel = Tapel::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();

        $data = SiswaRombel::with('siswa')
            ->where('tapel_id', $tapel->id)
            ->where('semester_id', $semester->id)
            ->where('rombel_id', $id)
            ->get();

        return response()->json($data);
    }
}
