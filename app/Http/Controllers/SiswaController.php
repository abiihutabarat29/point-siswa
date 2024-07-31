<?php

namespace App\Http\Controllers;

use App\Models\IDCard;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $menu = 'Siswa';
        $rombel = Rombel::all();
        if ($request->ajax()) {
            $data = Siswa::latest();
            return Datatables::of($data)
                ->addColumn('foto', function ($data) {
                    if ($data->foto == "male.png") {
                        $foto = '<center><img src="' . url("assets/img/avatars/male.png") .
                            '" width="50px" class="img rounded"><center>';
                    } elseif ($data->foto == "female.png") {
                        $foto = '<center><img src="' . url("assets/img/avatars/female.png") .
                            '" width="50px" class="img rounded"><center>';
                    } else {
                        $foto = '<center><img src="' . url("storage/siswa/" . $data->foto) .
                            '" width="50px" class="img rounded"><center>';
                    }
                    return $foto;
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
                ->addColumn('qr', function ($data) {
                    return QrCode::generate($data->nisn);
                })
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button type="button" class="dropdown-item edit"
                                data-bs-toggle="modal" data-bs-target="#modal" aria-controls="ajaxModel"
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
                ->rawColumns(['action', 'foto'])
                ->make(true);
        }

        return view('siswa.index', compact('menu', 'rombel'));
    }

    public function store(Request $request)
    {
        $message = array(
            'nisn.required'         => 'NISN harus diisi.',
            'name.required'         => 'Nama harus diisi.',
            'gender.required'       => 'Jenis Kelamin harus dipilih.',
            'pendaftaran.required'  => 'Jenis Pendaftaran harus dipilih.',
            'tmp_lahir.required'    => 'Tempat Lahir harus diisi.',
            'tgl_lahir.required'    => 'Tanggal Lahir harus diisi.',
            'foto.required'         => 'Foto harus diupload.',
            'foto.*.mimes'          => 'Tipe foto yang diunggah harus jpg, jpeg atau png.',
            'foto.*.max'            => 'Ukuran foto tidak boleh lebih dari 8 MB.',
            'pendaftaran.required'  => 'Jenis Pendaftaran harus dipilih.',
        );

        if ($request->hidden_id) {
            $ruleFoto       = 'mimes:jpg,jpeg,png|max:8048';
        } else {
            $ruleFoto       = 'required|mimes:jpg,jpeg,png|max:8048';
        }

        $validator = Validator::make($request->all(), [
            'nisn'      => 'required',
            'name'      => 'required',
            'gender'    => 'required',
            'pendaftaran'    => 'required',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required',
            'foto'      => $ruleFoto,
            'pendaftaran'  => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fileFoto = time() . '-' . $foto->getClientOriginalName();
            $foto->storeAs('public/siswa', $fileFoto);
            if ($request->hidden_id) {
                $oldFoto = Siswa::find($request->hidden_id);
                Storage::delete('public/siswa/' . $oldFoto->foto);
            }
        } elseif ($request->hidden_id) {
            $oldFoto = Siswa::find($request->hidden_id);
            $fileFoto = $oldFoto->foto;
        } else {
            $fileFoto = null;
        }

        $siswa = Siswa::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'nisn'        => $request->nisn,
                'name'        => $request->name,
                'gender'      => $request->gender,
                'tmp_lahir'   => $request->tmp_lahir,
                'tgl_lahir'   => $request->tgl_lahir,
                'foto'        => $fileFoto,
                'pendaftaran' => $request->pendaftaran,
                'tgl_masuk'   => $request->tgl_masuk,
            ]
        );

        User::updateOrCreate(
            [
                'id_card' => $request->nisn
            ],
            [
                'siswa_id'  => $siswa->id,
                'name'      => $request->name,
                'password'  => "12345678",
                'role_id'   => 4,
            ]
        );

        return response()->json(['success' => 'Siswa saved successfully.']);
    }

    public function edit($id)
    {
        $data = Siswa::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = Siswa::find($id);
        Storage::delete('public/siswa/' . $data->image);
        $data->delete();
        $user = User::find('id_card');
        $user->delete();
        return response()->json(['success' => 'Siswa deleted successfully.']);
    }

    public function get(Request $request)
    {
        $data = Siswa::where('kelas_id', $request->kelas_id)->get();
        return response()->json($data);
    }
}
