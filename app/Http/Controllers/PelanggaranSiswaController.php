<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\Rombel;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Tapel;
use App\Models\SiswaRombel;
use Carbon\Carbon;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            $smt = Semester::where('status', 1)->first();
            $data = Rombel::where('tapel_id', $tapel->id)->where('semester_id', $smt->id)->get();
            return DataTables::of($data)
                ->addColumn('tapel', function ($data) {
                    return $data->tapel->tahun . ' <span class="text-capitalize">' . $data->tapel->semester . '</span>';
                })
                ->addColumn('rombel', function ($data) {
                    return $data->name;
                })
                ->addColumn('jurusan', function ($data) {
                    return $data->jurusan->name;
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
        $menu = "Data Point Siswa " . $rombel->kelas->name . ' ' . $rombel->name;
        $siswaRombel = SiswaRombel::where('rombel_id', $rombel->id)->get();
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
                ->addColumn('nisn', function ($data) {
                    $nisn = $data->siswa->nisn;
                    return $nisn;
                })
                ->addColumn('siswa', function ($data) {
                    return $data->siswa->name;
                })
                ->addColumn('gender', function ($data) {
                    $gender = $data->siswa->gender === 'L' ? 'Laki-laki' : 'Perempuan';
                    return $gender;
                })
                ->addColumn('poin', function ($data) {
                    return '<center>' . PelanggaranSiswa::where('siswa_id', $data->siswa_id)
                        ->with('pelanggaran')
                        ->get()
                        ->sum(function ($pelanggaranSiswa) {
                            return $pelanggaranSiswa->pelanggaran->bobot;
                        }) . '</center>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="text-center">
                            <a href="' . route(
                        'pelanggaran-siswa.skors',
                        Crypt::encrypt($row->siswa_id)
                    ) . '" type="button"
                                class="btn btn-sm btn-primary">
                                <span class="tf-icons bx bx-error me-1"></span>Lihat Point
                        </div>';
                })
                ->rawColumns(['action', 'foto', 'poin'])
                ->make(true);
        }
        return view('pelanggaran-siswa.siswa', compact(
            'menu',
            'rombel',
            'id',
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
        $menu = "Point " . $siswa->name;
        $pelanggaran = Pelanggaran::all();
        if ($request->ajax()) {
            $id = Crypt::decrypt($id);
            $data = PelanggaranSiswa::where('siswa_id', $id)->get();
            return Datatables::of($data)
                ->addColumn('nama_siswa', function ($data) {
                    return $data->siswa->name;
                })
                ->addColumn('nama_pelanggaran', function ($data) {
                    return '<center>' . $data->pelanggaran->name . '</center>';
                })
                ->addColumn('poin', function ($data) {
                    return '<center>' . $data->pelanggaran->bobot . '</center>';
                })
                ->addColumn('action', function ($row) {
                    return '<center><div class="dropdown">
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
                    </div></center>';
                })
                ->rawColumns(['action', 'nama_pelanggaran', 'poin'])
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
        $data = PelanggaranSiswa::find($id);

        if ($data->foto) {
            Storage::delete('public/foto-pelanggaran/' . $data->foto);
        }
        $data->delete();
        return response()->json(['success' => 'Skors deleted successfully.']);
    }

    public function pointSiswa(Request $request)
    {
        $menu = 'Daftar Pelanggaran Siswa';
        $tapel = Tapel::where('status', 1)->first();
        $smt = Semester::where('status', 1)->first();
        $rombel = Rombel::where('tapel_id', $tapel->id)->where('semester_id', $smt->id)->get();
        $pelanggaran = Pelanggaran::all();

        if (Auth::user()->role_id == 4) {
            if ($request->ajax()) {
                $data = PelanggaranSiswa::select(
                    'pelanggaran_siswa.siswa_id',
                    'pelanggaran_siswa.rombel_id',
                    DB::raw('MAX(pelanggaran_siswa.user_id) as user_id'),
                    DB::raw('SUM(CASE WHEN pelanggaran_siswa.status = 1 THEN pelanggaran.bobot ELSE 0 END) as total_poin')
                )
                    ->leftJoin('pelanggaran', 'pelanggaran_siswa.pelanggaran_id', '=', 'pelanggaran.id')
                    ->leftJoin('siswa', 'pelanggaran_siswa.siswa_id', '=', 'siswa.id')
                    ->leftJoin('rombel', 'pelanggaran_siswa.rombel_id', '=', 'rombel.id')
                    ->leftJoin('users', 'pelanggaran_siswa.user_id', '=', 'users.id')
                    ->when(Auth::user()->role_id == 3, function ($query) {
                        return $query->where('pelanggaran_siswa.user_id', Auth::user()->id);
                    })
                    ->when(Auth::user()->role_id == 4, function ($query) {
                        return $query->where(function ($query) {
                            $query->where('pelanggaran_siswa.user_id', Auth::user()->id)
                                ->orWhere('users.role_id', 3);
                        });
                    })
                    ->groupBy('pelanggaran_siswa.siswa_id', 'pelanggaran_siswa.rombel_id')
                    ->get();

                return DataTables::of($data)
                    ->addColumn('foto', function ($data) {
                        $siswa = Siswa::find($data->siswa_id);
                        if ($siswa->foto == "male.png") {
                            $foto = '<center><img src="' . url("assets/img/avatars/male.png") .
                                '" width="50px" class="img rounded"><center>';
                        } elseif ($siswa->foto == "female.png") {
                            $foto = '<center><img src="' . url("assets/img/avatars/female.png") .
                                '" width="50px" class="img rounded"><center>';
                        } else {
                            $foto = '<center><img src="' . url("storage/siswa/" . $siswa->foto) .
                                '" width="50px" class="img rounded"><center>';
                        }
                        return $foto;
                    })
                    ->addColumn('nisn', function ($data) {
                        $siswa = Siswa::find($data->siswa_id);
                        return $siswa->nisn;
                    })
                    ->addColumn('rombel', function ($data) {
                        return $data->rombel->name;
                    })
                    ->addColumn('siswa', function ($data) {
                        $siswa = Siswa::find($data->siswa_id);
                        return $siswa->name;
                    })
                    ->addColumn('gender', function ($data) {
                        $siswa = Siswa::find($data->siswa_id);
                        return $siswa->gender === 'L' ? 'Laki-laki' : 'Perempuan';
                    })
                    ->addColumn('poin', function ($data) {
                        $colorClass = '';
                        if ($data->total_poin >= 0 && $data->total_poin <= 29) {
                            $colorClass = 'success';
                        } elseif ($data->total_poin >= 30 && $data->total_poin <= 59) {
                            $colorClass = 'primary';
                        } elseif ($data->total_poin >= 60 && $data->total_poin <= 89) {
                            $colorClass = 'warning';
                        } elseif ($data->total_poin >= 90) {
                            $colorClass = 'danger';
                        }
                        return '<center><span class="badge bg-label-' . $colorClass . '">' . $data->total_poin . '</span></center>';
                    })
                    ->addColumn('action', function ($row) {
                        return '
                            <div class="text-center">
                                <a href="' . route(
                            'point-pelanggaran-siswa.riwayat',
                            Crypt::encrypt($row->siswa_id)
                        ) . '" type="button" class="btn btn-sm btn-danger">
                                    <span class="tf-icons bx bx-error me-1"></span>Riwayat Skor
                            </div>';
                    })
                    ->rawColumns(['foto', 'siswa', 'poin', 'action'])
                    ->make(true);
            }
            return view('pelanggaran-siswa.skor-bk', compact('menu', 'rombel', 'pelanggaran'));
        } elseif (Auth::user()->role_id == 3) {
            if ($request->ajax()) {
                $data = PelanggaranSiswa::select(
                    'pelanggaran_siswa.siswa_id',
                    'pelanggaran_siswa.rombel_id',
                    DB::raw('MAX(pelanggaran_siswa.user_id) as user_id'),
                    DB::raw('SUM(CASE WHEN pelanggaran_siswa.status = 1 THEN pelanggaran.bobot ELSE 0 END) as total_poin')
                )
                    ->leftJoin('pelanggaran', 'pelanggaran_siswa.pelanggaran_id', '=', 'pelanggaran.id')
                    ->leftJoin('siswa', 'pelanggaran_siswa.siswa_id', '=', 'siswa.id')
                    ->leftJoin('rombel', 'pelanggaran_siswa.rombel_id', '=', 'rombel.id')
                    ->leftJoin('users', 'pelanggaran_siswa.user_id', '=', 'users.id')
                    ->when(Auth::user()->role_id == 3, function ($query) {
                        return $query->where('pelanggaran_siswa.user_id', Auth::user()->id);
                    })
                    ->when(Auth::user()->role_id == 4, function ($query) {
                        return $query->where(function ($query) {
                            $query->where('pelanggaran_siswa.user_id', Auth::user()->id)
                                ->orWhere('users.role_id', 3);
                        });
                    })
                    ->groupBy('pelanggaran_siswa.siswa_id', 'pelanggaran_siswa.rombel_id')
                    ->get();

                return DataTables::of($data)
                    ->addColumn('foto', function ($data) {
                        $siswa = Siswa::find($data->siswa_id);
                        if ($siswa->foto == "male.png") {
                            $foto = '<center><img src="' . url("assets/img/avatars/male.png") .
                                '" width="50px" class="img rounded"><center>';
                        } elseif ($siswa->foto == "female.png") {
                            $foto = '<center><img src="' . url("assets/img/avatars/female.png") .
                                '" width="50px" class="img rounded"><center>';
                        } else {
                            $foto = '<center><img src="' . url("storage/siswa/" . $siswa->foto) .
                                '" width="50px" class="img rounded"><center>';
                        }
                        return $foto;
                    })
                    ->addColumn('nisn', function ($data) {
                        $siswa = Siswa::find($data->siswa_id);
                        return $siswa->nisn;
                    })
                    ->addColumn('rombel', function ($data) {
                        return $data->rombel->name;
                    })
                    ->addColumn('siswa', function ($data) {
                        $siswa = Siswa::find($data->siswa_id);
                        return $siswa->name;
                    })
                    ->addColumn('gender', function ($data) {
                        $siswa = Siswa::find($data->siswa_id);
                        return $siswa->gender === 'L' ? 'Laki-laki' : 'Perempuan';
                    })
                    ->addColumn('action', function ($row) {
                        return '
                            <div class="text-center">
                                <a href="' . route(
                            'point-pelanggaran-siswa.riwayat',
                            Crypt::encrypt($row->siswa_id)
                        ) . '" type="button" class="btn btn-sm btn-danger">
                                    <span class="tf-icons bx bx-error me-1"></span>Riwayat Skor
                            </div>';
                    })
                    ->rawColumns(['foto', 'siswa', 'poin', 'action'])
                    ->make(true);
            }
            return view('pelanggaran-siswa.skor-guru', compact('menu', 'rombel', 'pelanggaran'));
        }
    }

    public function storePoint(Request $request)
    {
        $message = array(
            'rombel_id.required'        => 'Rombel harus dipilih.',
            'siswa_id.required'         => 'Siswa harus dipilih.',
            'pelanggaran_id.required'   => 'Jenis Pelanggaran harus dipilih.',
            'foto.image'                => 'Foto yang diupload bukan gambar.',
            'foto.mimes'                => 'Tipe foto yang diunggah harus jpg, jpeg atau png.',
            'foto.max'                  => 'Ukuran foto tidak boleh lebih dari 5 MB.',
        );

        $validator = Validator::make($request->all(), [
            'rombel_id'       => 'required',
            'siswa_id'        => 'required',
            'pelanggaran_id'  => 'required',
            'foto'            => 'image|mimes:jpg,jpeg,png|max:5048',
        ], $message);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $tapel = Tapel::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fileFoto = 'FOTO-PELANGGARAN-' . time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/foto-pelanggaran', $fileFoto);

            if ($request->hidden_id) {
                $oldFoto = PelanggaranSiswa::find($request->hidden_id);
                Storage::delete('public/foto-pelanggaran/' . $oldFoto->foto);
            }
        } elseif ($request->hidden_id) {
            $oldFoto = PelanggaranSiswa::find($request->hidden_id);
            $fileFoto = $oldFoto->foto;
        } else {
            $fileFoto = null;
        }

        PelanggaranSiswa::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'tapel_id'          => $tapel->id,
                'semester_id'       => $semester->id,
                'rombel_id'         => $request->rombel_id,
                'siswa_id'          => $request->siswa_id,
                'pelanggaran_id'    => $request->pelanggaran_id,
                'user_id'           => Auth::user()->id,
                'foto'              => $fileFoto,
                'keterangan'        => $request->keterangan,
            ]
        );

        return response()->json(['success' => 'Skors saved successfully.']);
    }

    public function riwayat(Request $request, $id)
    {
        $siswa = Siswa::where('id', Crypt::decrypt($id))->first();
        $rombel = SiswaRombel::where('siswa_id', $siswa->id)->first();
        $menu = "Riwayat Pelanggaran - " . $siswa->name;
        $pelanggaran = Pelanggaran::all();
        if (Auth::user()->role_id == 4) {
            if ($request->ajax()) {
                $id = Crypt::decrypt($id);
                $data = PelanggaranSiswa::select('pelanggaran_siswa.*', 'users.name as pelapor')
                    ->leftJoin('users', 'pelanggaran_siswa.user_id', '=', 'users.id')
                    ->when(Auth::user()->role_id == 3, function ($query) {
                        return $query->where('pelanggaran_siswa.user_id', Auth::user()->id);
                    })
                    ->when(Auth::user()->role_id == 4, function ($query) {
                        return $query->where(function ($query) {
                            $query->where('pelanggaran_siswa.user_id', Auth::user()->id)
                                ->orWhere('users.role_id', 3);
                        });
                    })
                    ->where('pelanggaran_siswa.siswa_id', $id)
                    ->get();

                return Datatables::of($data)
                    ->addColumn('nama_siswa', function ($data) {
                        return $data->siswa->name;
                    })
                    ->addColumn('nama_pelanggaran', function ($data) {
                        return '<center>' . $data->pelanggaran->name . '</center>';
                    })
                    ->addColumn('poin', function ($data) {
                        return '<center>' . $data->pelanggaran->bobot . '</center>';
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status == 0) {
                            $status = '<span class="badge bg-label-warning me-1">Pending</span>';
                        } elseif ($data->status == 1) {
                            $status = '<span class="badge bg-label-success me-1">Terkonfirmasi</span>';
                        } else {
                            $status = '<span class="badge bg-label-danger me-1">Ditolak</span>';
                        }
                        return '<center>' . $status . '</center>';
                    })
                    ->addColumn('pelapor', function ($data) {
                        return '<center>' . $data->pelapor . '</center>';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '';
                        if ($row->status == 0) {
                            $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-success btn-sm me-1 konfirmasi">
                                        <i class="tf-icons bx bx-check-circle"></i>
                                     </a>';
                            $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm me-1 tolak">
                                        <i class="tf-icons bx bx-x-circle"></i>
                                     </a>';
                        }

                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-nama="' . $row->siswa->name . '" class="btn btn-info btn-sm me-1 detail">
                                    <i class="tf-icons bx bx-group"></i>
                                 </a>';
                        return '<center>' . $btn . '</center>';
                    })

                    ->rawColumns(['action', 'nama_pelanggaran', 'poin', 'status',  'pelapor'])
                    ->make(true);
            }

            return view('pelanggaran-siswa.riwayat-bk', compact('menu', 'pelanggaran', 'id', 'siswa', 'rombel'));
        } elseif (Auth::user()->role_id == 3) {
            if ($request->ajax()) {
                $id = Crypt::decrypt($id);
                $data = PelanggaranSiswa::select('pelanggaran_siswa.*', 'users.name as pelapor')
                    ->leftJoin('users', 'pelanggaran_siswa.user_id', '=', 'users.id')
                    ->when(Auth::user()->role_id == 3, function ($query) {
                        return $query->where('pelanggaran_siswa.user_id', Auth::user()->id);
                    })
                    ->when(Auth::user()->role_id == 4, function ($query) {
                        return $query->where(function ($query) {
                            $query->where('pelanggaran_siswa.user_id', Auth::user()->id)
                                ->orWhere('users.role_id', 3);
                        });
                    })
                    ->where('pelanggaran_siswa.siswa_id', $id)
                    ->get();

                return Datatables::of($data)
                    ->addColumn('nama_siswa', function ($data) {
                        return $data->siswa->name;
                    })
                    ->addColumn('nama_pelanggaran', function ($data) {
                        return '<center>' . $data->pelanggaran->name . '</center>';
                    })
                    ->addColumn('poin', function ($data) {
                        return '<center>' . $data->pelanggaran->bobot . '</center>';
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status == 0) {
                            $status = '<span class="badge bg-label-warning me-1">Pending</span>';
                        } elseif ($data->status == 1) {
                            $status = '<span class="badge bg-label-success me-1">Terkonfirmasi</span>';
                        } else {
                            $status = '<span class="badge bg-label-danger me-1">Ditolak</span>';
                        }
                        return '<center>' . $status . '</center>';
                    })
                    ->addColumn('pelapor', function ($data) {
                        return '<center>' . $data->pelapor . '</center>';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" data-nama="' . $row->siswa->name . '" class="btn btn-info btn-sm me-1 detail"><i class="tf-icons bx bx-group"></i></a>';
                        return '<center>' . $btn . '</center>';
                    })
                    ->rawColumns(['action', 'nama_pelanggaran', 'poin', 'status', 'pelapor'])
                    ->make(true);
            }

            return view('pelanggaran-siswa.riwayat-guru', compact('menu', 'pelanggaran', 'id', 'siswa', 'rombel'));
        }
    }

    public function konfirmasiSkor(Request $request)
    {
        $skor = PelanggaranSiswa::find($request->id);
        if ($skor) {
            $skor->status = 1;
            $skor->save();
            return response()->json(['success' => 'Skor telah dikonfirmasi.']);
        } else {
            return response()->json(['error' => 'Skor gagal dikonfirmasi.']);
        }
    }

    public function tolakSkor(Request $request)
    {
        $skor = PelanggaranSiswa::find($request->id);

        $message = array(
            'alasan.required' => 'Mohon berikan alasan penolakan.',
        );
        $validator = Validator::make($request->all(), [
            'alasan' => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($skor) {
            $skor->status = 2;
            $skor->alasan = $request->alasan;
            $skor->save();
            return response()->json(['success' => 'Skor telah ditolak.']);
        } else {
            return response()->json(['error' => 'Skor gagal ditolak.']);
        }
    }

    public function show($id)
    {
        $data = PelanggaranSiswa::with(['siswa', 'rombel', 'pelanggaran'])
            ->leftJoin('users', 'pelanggaran_siswa.user_id', '=', 'users.id')
            ->select('pelanggaran_siswa.*', 'users.name as pelapor')
            ->where('pelanggaran_siswa.id', $id)
            ->first();

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'detail' => $data
        ]);
    }
}
