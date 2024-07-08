<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel;
use App\Models\Hari;
use App\Models\Jadwal;
use App\Models\JadwalMapel;
use App\Models\Mapel;
use App\Models\Rombel;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\SiswaAbsensi;
use App\Models\SiswaRombel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use \Yajra\Datatables\Datatables;

class SiswaAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $menu = 'Absensi Siswa';
        $today = Carbon::now()->translatedFormat('l');
        $hari = Hari::where('name', $today)->first();
        $currentTime = Carbon::now()->format('H:i:s');

        //     $getMapel = JadwalMapel::where('hari_id', $hari->id)
        //     ->where('start', '<=', $currentTime)
        //     ->where('end', '>', $currentTime)
        //     ->get();

        // $groupedSchedules = $getMapel->groupBy('mapel_id');

        // $jadwalMapel = [];

        // foreach ($groupedSchedules as $mapelId => $schedules) {
        //     $earliestStartTime = null;
        //     $latestEndTime = null;

        //     foreach ($schedules as $schedule) {
        //         if ($earliestStartTime === null || $schedule->start < $earliestStartTime) {
        //             $earliestStartTime = $schedule->start;
        //         }

        //         if ($latestEndTime === null || $schedule->end > $latestEndTime) {
        //             $latestEndTime = $schedule->end;
        //         }
        //     }

        //     $jadwalMapel[] = [
        //         "hari_id" => $hari->id,
        //         "mapel_id" => $mapelId,
        //         'start' => $earliestStartTime,
        //         'end' => $latestEndTime,
        //     ];
        // }

        if (Auth::user()->role_id == 1) {

            $uniqueMapelIds = DB::table('jadwal_mapel')
                ->join('guru_mapel', 'jadwal_mapel.guru_mapel_id', '=', 'guru_mapel.id')
                ->where('jadwal_mapel.hari_id', $hari->id)
                ->whereNotNull('jadwal_mapel.guru_mapel_id')
                ->select(DB::raw('MIN(jadwal_mapel.id) as id'))
                ->groupBy('jadwal_mapel.guru_mapel_id')
                ->pluck('id');

            $jadwalMapel = JadwalMapel::with('guru_mapel')
                ->whereIn('id', $uniqueMapelIds)
                ->orderBy('start')
                ->get();
        } elseif (Auth::user()->role_id == 3) {
            // $jadwalMapel = JadwalMapel::with('guru_mapel')
            // ->where('hari_id', $hari->id)
            // ->where('guru_mapel_id', '!=', null)
            // ->whereHas('guru_mapel', function ($query) {
            //     $query->where('guru_id', Auth::user()->guru_id);
            // })
            // ->get();

            $uniqueMapelIds = DB::table('jadwal_mapel')
                ->join('guru_mapel', 'jadwal_mapel.guru_mapel_id', '=', 'guru_mapel.id')
                ->where('jadwal_mapel.hari_id', $hari->id)
                ->where('guru_mapel.guru_id', Auth::user()->guru_id)
                ->whereNotNull('jadwal_mapel.guru_mapel_id')
                ->select(DB::raw('MIN(jadwal_mapel.id) as id'))
                ->groupBy('jadwal_mapel.guru_mapel_id')
                ->pluck('id');

            $jadwalMapel = JadwalMapel::with('guru_mapel')
                ->whereIn('id', $uniqueMapelIds)
                ->orderBy('start')
                ->get();
        }

        return view('absensi.index', compact('menu', 'jadwalMapel'));
    }

    public function presensi(Request $request, $id, $idm)
    {
        $menu = 'Presensi';
        $id = Crypt::decrypt($id);
        $idm = Crypt::decrypt($idm);
        $rombel = Rombel::find($id);
        $mapel = Mapel::find($idm);
        $guru = GuruMapel::where('rombel_id', $rombel->id)
            ->where('mapel_id', $mapel->id)
            ->first();
        $jadwal = Jadwal::where('rombel_id', $rombel->id)->first();
        if ($request->ajax()) {
            $today = now()->toDateString();
            $data = SiswaRombel::with('siswa')
                ->where('rombel_id', $rombel->id)
                ->latest();
            $absen = SiswaAbsensi::whereDate('tgl_absen', $today)
                ->pluck('siswa_id')
                ->toArray();
            return Datatables::of($data)
                ->addColumn('foto', function ($data) {
                    if ($data->siswa->foto == "male.png") {
                        $foto = '<center><img src="' . url("assets/img/avatars/male.png") .
                            '" width="50px" class="img rounded"><center>';
                    } elseif ($data->siswa->foto == "female.png") {
                        $foto = '<center><img src="' . url("assets/img/avatars/female.png") .
                            '" width="50px" class="img rounded"><center>';
                    } else {
                        $foto = '<center><img src="' . url("storage/siswa/" . $data->siswa->foto) .
                            '" width="50px" class="img rounded"><center>';
                    }
                    return $foto;
                })
                ->addColumn('nisn', function ($data) {
                    return $data->siswa->nisn;
                })
                ->addColumn('name', function ($data) {
                    return $data->siswa->name;
                })
                ->addColumn('status', function ($data) use ($absen) {
                    $msg = in_array($data->siswa->id, $absen) ? '<center><span class="badge bg-success">
                    OK
                </span></center>' : '<center><span class="badge bg-label-danger">
                Belum Absen
            </span></center>';
                    return $msg;
                })
                ->addColumn('action', function ($data) use ($absen) {
                    $act = in_array($data->siswa->id, $absen) ? '' : '
                    <center>
                        <button type="button" class="btn btn-sm btn-icon btn-primary scan"
                            data-bs-target="#modal-scan"
                            data-bs-toggle="modal" data-id="' . $data->siswa->id . '">
                            <i class="bx bx-scan"></i>
                        </button>
                    </center>';
                    return $act;
                })
                ->rawColumns(['action', 'foto', 'status'])
                ->make(true);
        }

        return view('absensi.presensi', compact('menu', 'rombel', 'guru', 'mapel'));
    }

    public function scanAbsensi(Request $request)
    {
        $nisn = $request->nisn;
        $siswaId = $request->siswa_id;

        $cekSiswaNisn = Siswa::where('nisn', $nisn)->first();
        if (!$cekSiswaNisn) {
            return response()->json(['errors' => ['Siswa tidak ditemukan.']]);
        }

        $cekSiswaId = Siswa::where('id', $siswaId)->first();
        if ($cekSiswaId->nisn !== $nisn) {
            return response()->json(['errors' => ['NISN tidak sesuai dengan siswa yang dipilih.']]);
        }

        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->format('H:i:s');

        $today = Carbon::now()->translatedFormat('l');
        $hari = Hari::where('name', $today)->first();

        $jadwalMapel = JadwalMapel::where('hari_id', $hari->id)
            ->where('start', '<=', $currentTime)
            ->where('end', '>=', $currentTime)
            ->first();

        if (!$jadwalMapel) {
            return response()->json(['errors' => ['Tidak ada jadwal mapel saat ini.']]);
        }

        $cekAbsen = SiswaAbsensi::where('siswa_id', $siswaId)
            ->where('jadwal_mapel_id', $jadwalMapel->id)
            ->whereDate('tgl_absen', $currentDate)
            ->exists();

        if ($cekAbsen) {
            return response()->json(['errors' => [$cekSiswaId->name . ' sudah absen pada jadwal mapel ini.']]);
        }

        SiswaAbsensi::create([
            'jadwal_mapel_id' => $jadwalMapel->id,
            'guru_id'         => Auth::user()->guru_id,
            'siswa_id'        => $siswaId,
            'tgl_absen'       => $currentDate,
            'jam_absen'       => $currentTime,
            'status'          => 'H',
        ]);

        return response()->json(['success' => $cekSiswaId->name . ' berhasil absen.'], 200);
    }
}
