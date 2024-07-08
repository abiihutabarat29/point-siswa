<?php

namespace App\Http\Controllers;

use App\Models\MapelKelompok;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class MapelKelompokController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MapelKelompok::where('parent', '0')->get();
            return Datatables::of($data)
                ->addColumn('kategori', function ($data) {
                    return '<div class="text-center">' . $data->kategori . '</div>';
                })
                ->addColumn('kode', function ($data) {
                    return '<div class="text-center">' . $data->kode . '</div>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown text-center">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button type="button" class="dropdown-item editKel"
                                data-id="' . $row->id . '">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </button>
                            <button type="button" class="dropdown-item deleteKel" data-bs-toggle="modal"
                            data-bs-target="#ajaxModelHps" data-id="' . $row->id . '">
                                <i class="bx bx-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>';
                })
                ->rawColumns(['kategori', 'kode', 'action'])
                ->make(true);
        }
    }

    public function subKelompok(Request $request)
    {
        if ($request->ajax()) {
            $data = MapelKelompok::whereNot('parent', '0')->get();
            return Datatables::of($data)
                ->addColumn('kategori', function ($data) {
                    return '<div class="text-center">' . $data->kategori . '</div>';
                })
                ->addColumn('kode', function ($data) {
                    return '<div class="text-center">' . $data->kode . '</div>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown text-center">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button type="button" class="dropdown-item editSub"
                                data-id="' . $row->id . '">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </button>
                            <button type="button" class="dropdown-item deleteKel" data-bs-toggle="modal"
                            data-bs-target="#ajaxModelHps" data-id="' . $row->id . '">
                                <i class="bx bx-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>';
                })
                ->rawColumns(['kategori', 'kode', 'action'])
                ->make(true);
        }
    }

    public function storeKel(Request $request)
    {
        $message = array(
            'kodeKel.required'         => 'Kode Kelompok harus diisi.',
            'nameKel.required'         => 'Nama harus diisi.',
            'kategoriKel.required'     => 'Kategori harus dipilih.',
        );

        $validator = Validator::make($request->all(), [
            'kodeKel'      => 'required',
            'nameKel'      => 'required',
            'kategoriKel'  => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        MapelKelompok::updateOrCreate(
            [
                'id' => $request->hidden_idKel
            ],
            [
                'kode'          => $request->kodeKel,
                'name'          => $request->nameKel,
                'kategori'      => $request->kategoriKel,
                'parent'        => 0,
            ]
        );

        return response()->json(['success' => 'Kelompok saved successfully.']);
    }

    public function storeSub(Request $request)
    {
        $message = array(
            'kodeSub.required'         => 'Kode Kelompok harus diisi.',
            'nameSub.required'         => 'Nama harus diisi.',
            'parentSub.required'       => 'Kel. Utama harus dipilih.',
        );

        $validator = Validator::make($request->all(), [
            'kodeSub'      => 'required',
            'nameSub'      => 'required',
            'parentSub'    => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $kategori = MapelKelompok::where('id', $request->parentSub)->first();

        MapelKelompok::updateOrCreate(
            [
                'id' => $request->hidden_idSub
            ],
            [
                'kode'          => $request->kodeSub,
                'name'          => $request->nameSub,
                'parent'        => $request->parentSub,
                'kategori'      => $kategori->kategori,
            ]
        );

        return response()->json(['success' => 'Sub Kelompok saved successfully.']);
    }

    public function edit($id)
    {
        $data = MapelKelompok::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        MapelKelompok::find($id)->delete();
        return response()->json(['success' => 'Kelompok / Sub Kelompok deleted successfully.']);
    }
}
