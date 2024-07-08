<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use \Yajra\Datatables\Datatables;
use App\Models\SiswaRombel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PelanggaranSiswaController extends Controller
{
    public function index(Request $request)
    {
        $menu = 'Point Siswa';
        $kelas = Kelas::all();
        $jurusan = Jurusan::where('id', '!=', 1)->get();
        $guru = Guru::all();
        if ($request->ajax()) {
            $tapel = Tapel::where('status', 1)->first();
            $data = Rombel::where('tapel_id', $tapel->id)->get();
            return DataTables::of($data)
                ->addColumn('tapel', function ($data) {
                    return $data->tapel->tahun . ' <span class="text-capitalize">' . $data->tapel->semester . '</span>';
                })
                ->addColumn('rombel', function ($data) {
                    return $data->kelas->name . ' ' . $data->name;
                })
                ->addColumn('jurusan', function ($data) {
                    return $data->jurusan->short_name;
                })
                ->addColumn('guru', function ($data) {
                    return $data->guru->name ?? '<span class="text-danger"><i>empty</i></span>';
                })
                ->addColumn('jlh_siswa', function ($data) {
                    return '<center>' . $data->siswa_rombel->count() . '<center>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="text-center">
                            <a href="' . route('pelanggaran-siswa.siswa', Crypt::encrypt($row->id)) . '" type="button"
                                class="btn btn-sm btn-primary">
                                <span class="tf-icons bx bx-child me-1"></span>View Siswa
                            </a>
                        </div>';
                })
                ->rawColumns(['action', 'tapel', 'guru', 'jlh_siswa'])
                ->make(true);
        }

        return view('pelanggaran-siswa.index', compact('menu', 'guru', 'jurusan', 'kelas'));
    }

    public function siswa(Request $request, $id)
    {
        $rombel = Rombel::where('id', Crypt::decrypt($id))->first();
        $menu = "Data Skor Siswa " . $rombel->kelas->name . ' ' . $rombel->name;
        $siswaRombel = SiswaRombel::where('rombel_id', $rombel->id)->get();
        $male = $siswaRombel->filter(function ($siswaRombel) {
            return $siswaRombel->siswa->gender === 'L';
        })->count();
        $female = $siswaRombel->filter(function ($siswaRombel) {
            return $siswaRombel->siswa->gender === 'P';
        })->count();
        $pelanggaran = Pelanggaran::all();

        if ($request->ajax()) {
            $id = Crypt::decrypt($id);
            $data = SiswaRombel::where('rombel_id', $id)->get();
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
                ->addColumn('siswa', function ($data) {
                    return $data->siswa->name;
                })
                ->addColumn('gender', function ($data) {
                    if ($data->gender === "L") {
                        $gender = "Laki-Laki";
                    } else {
                        $gender = "Perempuan";
                    }
                    return $gender;
                })
                ->addColumn('tgl_lahir', function ($data) {
                    return Carbon::parse($data->tgl_lahir)->isoFormat('D MMMM Y');
                })
                ->addColumn('poin', function ($data) {
                    return PelanggaranSiswa::where('siswa_id', $data->siswa_id)
                        ->with('pelanggaran') // Memuat relasi pelanggaran
                        ->get()
                        ->sum(function ($pelanggaranSiswa) {
                            return $pelanggaranSiswa->pelanggaran->bobot;
                        });
                })->addColumn('action', function ($row) {
                    return '
                        <div class="text-center">
                            <a href="' . route(
                        'pelanggaran-siswa.skors',
                        Crypt::encrypt($row->siswa_id)
                    ) . '" type="button"
                                class="btn btn-sm btn-primary">
                                <span class="tf-icons bx bx-error me-1"></span>View Skors
                            </a>
                        </div>';
                })
                ->rawColumns(['action', 'foto'])
                ->make(true);
        }
        return view('pelanggaran-siswa.siswa', compact(
            'menu',
            'rombel',
            'id',
            'male',
            'female',
            'siswaRombel',
            'pelanggaran'
        ));
    }

    public function store(Request $request)
    {
        $message = array(
            'siswa_id.required'         => 'Siswa harus dipilih.',
            'pelanggaran_id.required'   => 'Jenis Pelanggaran harus dipilih.',
        );

        $validator = Validator::make($request->all(), [
            'siswa_id'        => 'required',
            'pelanggaran_id'        => 'required',
        ], $message);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        PelanggaranSiswa::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'siswa_id'          => $request->siswa_id,
                'pelanggaran_id'    => $request->pelanggaran_id,
            ]
        );

        return response()->json(['success' => 'Skors saved successfully.']);
    }


    public function skors(Request $request, $id)
    {
        $siswa = Siswa::where('id', Crypt::decrypt($id))->first();
        $rombel = SiswaRombel::where('siswa_id', $siswa->id)->first();
        $menu = "Skor " . $siswa->name;
        $pelanggaran = Pelanggaran::all();
        if ($request->ajax()) {
            $id = Crypt::decrypt($id);
            $data = PelanggaranSiswa::where('siswa_id', $id)->get();
            return Datatables::of($data)
                ->addColumn('nama_siswa', function ($data) {
                    return $data->siswa->name;
                })
                ->addColumn('nama_pelanggaran', function ($data) {
                    return $data->pelanggaran->name;
                })
                ->addColumn('poin', function ($data) {
                    return $data->pelanggaran->bobot;
                })
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button type="button" class="dropdown-item edit"
                                data-bs-toggle="offcanvas" data-bs-target="#ajaxModel" aria-controls="ajaxModel"
                                data-id="' . $row->id . '">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </button>
                            <button type="button" class="dropdown-item delete" data-bs-toggle="modal"
                            data-bs-target="#ajaxModelHps" data-id="' . $row->id . '">
                                <i class="bx bx-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pelanggaran-siswa.skors', compact('menu', 'pelanggaran', 'id', 'siswa', 'rombel'));
    }

    public function edit($id)
    {
        $data = PelanggaranSiswa::find($id);
        return response()->json($data);
    }

    public function storeSiswa(Request $request)
    {
        $message = array(
            'pelanggaran_id.required'   => 'Jenis Pelanggaran harus dipilih.',
        );

        $validator = Validator::make($request->all(), [
            'pelanggaran_id'        => 'required',
        ], $message);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        PelanggaranSiswa::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'siswa_id'          => $request->siswa_id,
                'pelanggaran_id'    => $request->pelanggaran_id,
            ]
        );

        return response()->json(['success' => 'Skors saved successfully.']);
    }

    public function destroy($id)
    {
        PelanggaranSiswa::find($id)->delete();
        return response()->json(['success' => 'Skors deleted successfully.']);
    }
}
