<?php

namespace App\Http\Controllers;

use App\Imports\ImportMapel;
use App\Models\Mapel;
use App\Models\MapelKelompok;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $menu = 'Mata Pelajaran';
        $kelompokSub = MapelKelompok::where('parent', "0")->get();
        $kelompok = MapelKelompok::all();
        if ($request->ajax()) {
            $data = Mapel::orderBy('kode');

            return Datatables::of($data)
                ->addColumn('kode', function ($data) {
                    return '<div class="text-center">' . $data->kode . '</div>';
                })
                ->addColumn('kelompok', function ($data) {
                    return '<div class="text-center">' . $data->kelompok->kode . '</div>';
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == 1) {
                        return '<div class="text-center "><span class="badge bg-success">Aktif</span></div>';
                    } else {
                        return '<div class="text-center "><span class="badge bg-danger">Nonaktif</span></div>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown text-center">
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
                ->rawColumns(['action', 'kode', 'kelompok', 'status'])
                ->make(true);
        }

        return view('mapel.index', compact('menu', 'kelompok', 'kelompokSub'));
    }

    public function store(Request $request)
    {
        $message = array(
            'name.required'         => 'Mata Pelajaran harus diisi.',
            'kode.required'         => 'Kode harus diisi.',
            'jurusan_id.required'   => 'Jurusan harus dipilih.',
            'kelompok_id.required'  => 'Kelompok harus dipilih.',
            'status.required'       => 'Status harus dipilih.',
        );

        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'kode'          => 'required',
            'kelompok_id'   => 'required',
            'status'        => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        Mapel::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'name'           => $request->name,
                'kode'           => $request->kode,
                'kelompok_id'    => $request->kelompok_id,
                'status'         => $request->status,
            ]
        );

        return response()->json(['success' => 'Mata Pelajaran saved successfully.']);
    }

    public function edit($id)
    {
        $data = Mapel::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        Mapel::find($id)->delete();
        return response()->json(['success' => 'Mata Pelajaran deleted successfully.']);
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
        Excel::import(new ImportMapel, $file);
        return response()->json(['success' => 'Mapel Import successful!']);
    }
}
