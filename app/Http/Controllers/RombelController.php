<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Rombel;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\SiswaRombel;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class RombelController extends Controller
{
    public function index(Request $request)
    {
        $menu = 'Kelas/Rombel';
        $kelas = Kelas::all();
        $jurusan = Jurusan::where('id', '!=', 1)->get();
        $guru = Guru::all();
        if ($request->ajax()) {
            $tapel = Tapel::where('status', 1)->first();
            $semester = Semester::where('status', 1)->first();
            $data = Rombel::with('siswa_rombel')->where('tapel_id', $tapel->id)
                ->where('semester_id', $semester->id)->get();
            return DataTables::of($data)
                ->addColumn('jurusan', function ($data) {
                    return $data->jurusan->name;
                })
                ->addColumn('guru', function ($data) {
                    return $data->guru->name ?? '<span class="text-danger"><i>empty</i></span>';
                })
                ->addColumn('jlh_siswa', function ($data) {
                    $jumlah = '<center>' . $data->siswa_rombel->count() . '</center>';
                    return $jumlah;
                })
                ->addColumn('action', function ($row) {
                    return '
                    <div class="text-center">
                        <a href="' . route("rombel.show", Crypt::encrypt($row->id)) . '" class="btn btn-sm btn-icon btn-warning">
                            <i class="bx bx-search-alt"></i>
                        </a>
                        <a href="' . route("rombel.edit", Crypt::encrypt($row->id)) . '" class="btn btn-sm btn-icon btn-primary">
                            <i class="bx bxs-edit-alt"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-danger delete" data-bs-toggle="modal"
                            data-bs-target="#ajaxModelHps" data-id="' . $row->id . '">
                            <i class="bx bxs-trash-alt"></i>
                        </button>
                    </div>';
                })
                ->rawColumns(['action', 'guru', 'jlh_siswa'])
                ->make(true);
        }

        return view('rombel.index', compact('menu', 'guru', 'jurusan', 'kelas'));
    }

    public function create()
    {
        $menu = "Tambah Kelas/Rombel";
        $kelas = Kelas::all();
        $jurusan = Jurusan::all();
        $tapel = Tapel::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();
        $siswa = Siswa::whereNotIn('id', function ($query) use ($tapel, $semester) {
            $query->select('siswa_id')
                ->from('siswa_rombel')
                ->where('tapel_id', $tapel->id)
                ->where('semester_id', $semester->id);
        })->get();
        return view('rombel.data', compact('menu', 'kelas', 'jurusan', 'siswa'));
    }

    public function store(Request $request)
    {
        $message = array(
            'name.required'             => 'Nama Kelas harus diisi.',
            'kelas_id.required'         => 'Kelas harus dipilih.',
            'jurusan_id.required'       => 'Jurusan harus dipilih.',
            'siswa_id.required'         => 'Ketua Kelas harus dipilih.',
            'siswa_rombel.*.required'   => 'Siswa Rombel harus diisi.',
        );

        $validator = Validator::make($request->all(), [
            'name'              => 'required',
            'kelas_id'          => 'required',
            'jurusan_id'        => 'required',
            'siswa_id'          => 'required',
            'siswa_rombel.*'    => 'required',
        ], $message);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $tapel = Tapel::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();

        $rombel = Rombel::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'name'          => $request->name,
                'kelas_id'      => $request->kelas_id,
                'jurusan_id'    => $request->jurusan_id,
                'tapel_id'      => $tapel->id,
                'semester_id'   => $semester->id,
                'siswa_id'      => $request->siswa_id,
            ]
        );

        $selectedSiswa = explode(',', $request->selected_siswa);
        foreach ($selectedSiswa as $siswa_id) {
            SiswaRombel::create([
                'tapel_id' => $tapel->id,
                'semester_id' => $semester->id,
                'siswa_id' => $siswa_id,
                'rombel_id' => $rombel->id,
                'kelas_id' => $request->kelas_id,
            ]);
        }

        return redirect()->route('rombel.index')->with(['success' => 'Rombel saved successfully.']);
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $data = Rombel::find($id);
        $tapel = Tapel::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();
        $siswa = SiswaRombel::where('rombel_id', $id)->where('tapel_id', $tapel->id)
            ->where('semester_id', $semester->id)->get();
        $menu = "Kelas/Rombel " . $data->name;
        return view('rombel.show', compact('menu', 'data', 'siswa'));
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $kelas = Kelas::all();
        $jurusan = Jurusan::all();
        $tapel = Tapel::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();
        $siswa = Siswa::whereNotIn('id', function ($query) use ($tapel, $semester) {
            $query->select('siswa_id')
                ->from('siswa_rombel')
                ->where('tapel_id', $tapel->id)
                ->where('semester_id', $semester->id);
        })->get();
        $data = Rombel::find($id);
        $menu = "Edit Kelas/Rombel " . $data->name;
        return view('rombel.edit', compact('menu', 'kelas', 'jurusan', 'siswa', 'data'));
    }

    public function destroy($id)
    {
        Rombel::find($id)->delete();
        return response()->json(['success' => 'Rombel deleted successfully.']);
    }

    public function getData()
    {
        $data = Rombel::get(['id', 'name']);
        return response()->json($data);
    }
}
