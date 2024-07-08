<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel;
use App\Models\Hari;
use App\Models\Jadwal;
use App\Models\JadwalIstirahat;
use App\Models\JadwalMapel;
use App\Models\Mapel;
use App\Models\Rombel;
use App\Models\Semester;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index()
    {
        $menu = "Jadwal Pelajaran";
        $tapel = Tapel::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();
        $rombel = Rombel::where('tapel_id', $tapel->id)->where('semester_id', $semester->id)->get();
        $id = null;
        return view('jadwal.index', compact('menu', 'rombel', 'tapel', 'semester', 'id'));
    }

    public function rombel($id)
    {
        $id = Crypt::decrypt($id);
        $tapel = Tapel::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();
        $rombel = Rombel::where('tapel_id', $tapel->id)->where('semester_id', $semester->id)->get();
        $kelas = Rombel::find($id);
        $menu = "Jadwal Pelajaran Kelas " . $kelas->name;
        $hari = Hari::all();
        $mapel = GuruMapel::where('rombel_id', $kelas->id)->get();
        // dd($mapel);
        $jadwal = Jadwal::with('jadwal_istirahat')->where('rombel_id', $id)->first();
        if ($jadwal) {
            $jadwal_istirahat = JadwalIstirahat::where('jadwal_id', $jadwal->id)->get();
            $jadwal_mapels = JadwalMapel::where('jadwal_id', $jadwal->id)->get();
        } else {
            $jadwal_istirahat = null;
            $jadwal_mapels = null;
        }

        return view('jadwal.index', compact(
            'menu',
            'rombel',
            'tapel',
            'semester',
            'kelas',
            'id',
            'jadwal',
            'hari',
            'jadwal_istirahat',
            'mapel',
            'jadwal_mapels'
        ));
    }

    public function store(Request $request, $id)
    {

        $message = array(
            'durasi.required'           => 'Durasi Mapel harus diisi.',
            'jam_mulai.required'        => 'Jam mulai harus dipilih.',
            'jlh_mapel.required'        => 'Jumlah mapel harus diisi.',
            'jlh_istirahat.required'    => 'Jumlah istirahat harus dipilih.',
        );

        $validator = Validator::make($request->all(), [
            'durasi'        => 'required',
            'jam_mulai'     => 'required',
            'jlh_mapel'     => 'required',
            'jlh_istirahat' => 'required',
        ], $message);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $rombel_id = Crypt::decrypt($id);
        $tapel = Tapel::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();

        $jadwal = Jadwal::updateOrCreate(
            [
                'rombel_id'            =>  $rombel_id
            ],
            [
                'tapel_id'      => $tapel->id,
                'semester_id'   => $semester->id,
                'rombel_id'     => $rombel_id,
                'durasi'        => $request->durasi,
                'jam_mulai'     => $request->jam_mulai,
                'jlh_mapel'     => $request->jlh_mapel,
                'jlh_istirahat' => $request->jlh_istirahat,
            ]
        );

        $jadwal_istirahat = JadwalIstirahat::where('jadwal_id', $jadwal->id)->get();

        foreach ($jadwal_istirahat as $istirahat) {
            $istirahat->delete();
        }

        if ($request->has('jam_ke') && $request->has('durasi_istirahat')) {
            $jam_ke = $request->jam_ke;
            $durasi_istirahat = $request->durasi_istirahat;

            if (count($jam_ke) == count($durasi_istirahat)) {
                for ($i = 0; $i < count($jam_ke); $i++) {
                    JadwalIstirahat::create([
                        'jadwal_id' => $jadwal->id,
                        'jam_ke'    => $jam_ke[$i],
                        'durasi'    => $durasi_istirahat[$i],
                    ]);
                }
            }
        }
        return redirect()->route('jadwal.rombel', $id)->with(['success' => 'Jadwal saved successfully.']);
    }

    public function storeMapel(Request $request, $id)
    {
        $rombel_id = Crypt::decrypt($id);
        $tapel = Tapel::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();
        $jadwal_id = Jadwal::where('tapel_id', $tapel->id)
            ->where('semester_id', $semester->id)
            ->where('rombel_id', $rombel_id)
            ->first();

        if (!$jadwal_id) {
            return response()->json(['error' => 'Jadwal not found.'], 404);
        }

        $istirahats = JadwalIstirahat::where('jadwal_id', $jadwal_id->id)->get();

        // Jumlah entri waktu istirahat
        $jumlah_istirahat = count($istirahats);

        foreach ($request->data as $data) {
            $prev_jadwal_mapel = JadwalMapel::where('jadwal_id', $jadwal_id->id)
                ->where('hari_id', $data['hari_id'])
                ->where('jam_ke', $data['jam_ke'] - 1)
                ->first();

            $start = null;
            $end = null;

            if ($data['jam_ke'] == 1) {
                $start = Carbon::parse($jadwal_id->jam_mulai);
            } elseif ($prev_jadwal_mapel) {
                $prev_end = Carbon::parse($prev_jadwal_mapel->end);
                $start = $prev_end;
            }

            if ($start) {
                $durasi = intval($jadwal_id->durasi);
                $end = $start->copy()->addMinutes($durasi);
            } else {
                $istirahat = JadwalIstirahat::where('jadwal_id', $jadwal_id->id)
                    ->where('jam_ke', $data['jam_ke'])
                    ->first();
                if ($jumlah_istirahat > 0) {
                    $durasi_istirahat = intval($istirahats[0]->durasi);
                    $durasi_jadwal = intval($jadwal_id->durasi);
                    $prev_jadwal_mapel = JadwalMapel::where('jadwal_id', $jadwal_id->id)
                        ->where('hari_id', $data['hari_id'])
                        ->where('jam_ke', $data['jam_ke'] - 2)
                        ->first();
                    $prev_end = Carbon::parse($prev_jadwal_mapel->end);
                    $start = $prev_end->copy()->addMinutes($durasi_istirahat);
                    $end = $start->copy()->addMinutes($durasi_jadwal);
                } else {
                    $end = null;
                }
            }

            if (isset($data['jam_ke']) && isset($data['hari_id'])) {
                JadwalMapel::updateOrCreate(
                    [
                        'jadwal_id' =>  $jadwal_id->id,
                        'hari_id' => $data['hari_id'],
                        'jam_ke' => $data['jam_ke'],
                    ],
                    [
                        'jadwal_id' =>  $jadwal_id->id,
                        'hari_id' => $data['hari_id'],
                        'guru_mapel_id' => $data['guru_mapel_id'],
                        'jam_ke' => $data['jam_ke'],
                        'start' => $start ? $start->toTimeString() : null,
                        'end' => $end ? $end->toTimeString() : null,
                    ]
                );
            }
        }

        return response()->json(['success' => 'Jadwal Pelajaran saved successfully.']);
    }
}
