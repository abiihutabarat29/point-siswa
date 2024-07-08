<?php

namespace App\Http\Controllers;

use App\Imports\ImportGuru;
use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Jabatan;
use App\Models\JabatanGuru;
use App\Models\Mapel;
use App\Models\Rombel;
use App\Models\Semester;
use App\Models\Tapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GuruController extends Controller
{
    public function index()
    {
        $menu = "Data Guru";
        $tapel = Tapel::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();
        $guru = Guru::with(['jabatan_guru', 'guru_mapel'])->paginate(9);

        return view('guru.index', compact('menu', 'guru', 'tapel', 'semester'));
    }

    public function show($id)
    {
        $menu = "Profile Guru";
        $id = Crypt::decrypt($id);
        $guru = Guru::with('jabatan_guru')->where('id', $id)->first();
        return view('guru.data', compact('menu', 'guru'));
    }

    public function store(Request $request)
    {
        $message = array(
            'kode.required'         => 'Kode guru harus diisi.',
            'name.required'         => 'Nama harus diisi.',
            'nip.required'          => 'NIP harus diisi.',
            'status.required'       => 'Status guru harus diisi.',
            'gender.required'       => 'Jenis Kelamin harus dipilih.',
            'agama.required'        => 'Agama harus dipilih.',
            'tmp_lahir.required'    => 'Tempat Lahir harus diisi.',
            'tgl_lahir.required'    => 'Tanggal Lahir harus diisi.',
            'tlp.required'          => 'No. HP harus diisi.',
            'tlp.unique'            => 'No. HP sudah terdaftar.',
            'tlp.numeric'           => 'No. HP harus berupa angka.',
            'photo.required'        => 'Foto harus diupload.',
            'photo.*.mimes'         => 'Tipe foto yang diunggah harus jpg, jpeg atau png.',
            'photo.*.max'           => 'Ukuran foto tidak boleh lebih dari 8 MB.',
        );

        if ($request->hidden_id) {
            $ruleNohp       = 'numeric';
            $ruleFoto       = 'mimes:jpg,jpeg,png|max:8048';
        } else {
            $ruleNohp       = 'required|unique:guru,tlp|numeric';
            $ruleFoto       = 'required|mimes:jpg,jpeg,png|max:8048';
        }

        $validator = Validator::make($request->all(), [
            'nip'       => 'required',
            'name'      => 'required',
            'gender'    => 'required',
            'tmp_lahir' => 'required',
            'tlp'       => $ruleNohp,
            'tgl_lahir' => 'required',
            'photo'     => $ruleFoto,
            'status'    => 'required'
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($request->hasFile('photo')) {
            $foto = $request->file('photo');
            $fileFoto = time() . '-' . $foto->getClientOriginalName();
            $foto->storeAs('public/guru', $fileFoto);
            if ($request->hidden_id) {
                $oldFoto = Guru::find($request->hidden_id);
                Storage::delete('public/guru/' . $oldFoto->photo);
            }
        } elseif ($request->hidden_id) {
            $oldFoto = Guru::find($request->hidden_id);
            $fileFoto = $oldFoto->photo;
        } else {
            $fileFoto = null;
        }

        Guru::updateOrCreate(
            [
                'id' =>  $request->hidden_id
            ],
            [
                'kode'      => $request->kode,
                'nip'       => $request->nip,
                'nik'       => $request->nik,
                'name'      => $request->name,
                'gender'    => $request->gender,
                'agama'     => $request->agama,
                'tmp_lahir' => $request->tmp_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'address'   => $request->address,
                'tlp'       => $request->tlp,
                'status'    => $request->status,
                'photo'     => $fileFoto
            ]
        );

        User::updateOrCreate(
            [
                'id_card' => $request->nip
            ],
            [
                'id_card'   => $request->nip,
                'name'      => $request->name,
                'password'  => "12345678",
                'role_id'   => 3,
            ]
        );
        return redirect()->route('guru.index')->with(['success' => 'Profile Guru saved successfully.']);
    }

    public function edit($id)
    {
        $data = Guru::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = Guru::find($id);
        Storage::delete('public/guru/' . $data->image);
        $data->delete();
        $user = User::find('id_card');
        if (isset($user)) {
            $user->delete();
        }

        return response()->json(['success' => 'Guru deleted successfully.']);
    }

    public function jabatanGet($id)
    {
        $id = Crypt::decrypt($id);
        $guru = Guru::where('id', $id)->first();
        $menu = "Edit Jabatan Guru";
        $mapel = Mapel::all();
        $jabatan = Jabatan::all();
        $guruId = $guru->id;
        $rombel = Rombel::whereDoesntHave('jabatan_guru')
            ->orWhereHas('jabatan_guru', function ($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->get();
        $rombel_mapel = Rombel::get();

        return view('guru.jabatan', compact('menu', 'guru', 'mapel', 'jabatan', 'rombel', 'id', 'rombel_mapel'));
    }

    public function jabatanUpdate(Request $request, $id)
    {

        $message = array(
            'jabatan_id.required'   => 'Jabatan guru harus dipilih.',
        );

        $validator = Validator::make($request->all(), [
            'jabatan_id'    => 'required',
        ], $message);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $tapel = Tapel::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();

        if ($request->jabatan_id == 4) {
            JabatanGuru::updateOrCreate(
                [
                    'guru_id' =>  $id
                ],
                [
                    'tapel_id'      => $tapel->id,
                    'semester_id'   => $semester->id,
                    'guru_id'       => $id,
                    'jabatan_id'    => $request->jabatan_id,
                    'rombel_id'     => $request->rombel_id,
                ]
            );

            Rombel::updateOrCreate(
                [
                    'id'        => $request->rombel_id
                ],
                [
                    'guru_id'   => $request->guru_id,
                ]
            );
        } else {
            JabatanGuru::updateOrCreate(
                [
                    'guru_id' =>  $id
                ],
                [
                    'tapel_id'      => $tapel->id,
                    'semester_id'   => $semester->id,
                    'guru_id'       => $id,
                    'jabatan_id'    => $request->jabatan_id,
                    'rombel_id'      => null,
                ]
            );

            $old_rombel = Rombel::where('guru_id', $id)->first();
            if ($old_rombel) {
                Rombel::updateOrCreate(
                    [
                        'id'        => $old_rombel->id
                    ],
                    [
                        'guru_id'   => null,
                    ]
                );
            }
        }

        GuruMapel::where('tapel_id', $tapel->id)
            ->where('semester_id', $semester->id)
            ->where('guru_id', $request->guru_id)
            ->delete();

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'mapel_') === 0 && is_array($value)) {
                $nomor_mapel = substr($key, 6);
                foreach ($value as $kelas => $mapel_id) {
                    $nomor_kelas = substr($kelas, 6);

                    GuruMapel::create([
                        'tapel_id'    => $tapel->id,
                        'semester_id' => $semester->id,
                        'mapel_id'    => $nomor_mapel,
                        'guru_id'     => $request->guru_id,
                        'rombel_id'   => $nomor_kelas,
                    ]);
                }
            }
        }

        return redirect()->route('guru.index')->with(['success' => 'Jabatan Guru saved successfully.']);
    }

    public function import(Request $request)
    {
        $message = array(
            'file.required' => 'File harus diupload.',
        );

        $validator = Validator::make($request->all(), [
            'file'  => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $file = $request->file('file');
        $excelData = Excel::toArray(new ImportGuru, $file);
        foreach ($excelData[0] as $row) {
            $guru = Guru::create([
                'name'          => $row['name'],
                'tmp_lahir'     => $row['tmp_lahir'],
                'tgl_lahir'     => $row['tgl_lahir'],
                'gender'        => $row['gender'],
                'nip'           => $row['nip'],
                'status'        => $row['status'],
            ]);

            User::create(
                [
                    'id_card'   => $guru->nip,
                    'name'      => $guru->name,
                    'password'  => "12345678",
                    'role_id'   => 3,
                ]
            );
        }

        return response()->json(['success' => 'Guru Import successful!']);
    }
}
