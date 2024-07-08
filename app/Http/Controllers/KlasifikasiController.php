<?php

namespace App\Http\Controllers;

use App\Models\Klasifikasi;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class KlasifikasiController extends Controller
{
    public function index(Request $request)
    {
        $menu = 'Klasifikasi';
        if ($request->ajax()) {
            $data = Klasifikasi::latest();
            return Datatables::of($data)
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

        return view('klasifikasi.index', compact('menu'));
    }

    public function store(Request $request)
    {
        $message = array(
            'kode.required'   => 'Kode harus diisi.',
            'kode.numeric'    => 'Kode harus angka.',
            'kode.unique'     => 'Kode sudah ada.',
            'name.required'   => 'Nama Klasifikasi harus diisi.',
        );

        $validator = Validator::make($request->all(), [
            'kode'    => 'required|numeric|unique:klasifikasi,kode',
            'name'    => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        Klasifikasi::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'kode'    => $request->kode,
                'name'    => $request->name,
            ]
        );

        return response()->json(['success' => 'Klasifikasi saved successfully.']);
    }

    public function edit($id)
    {
        $data = Klasifikasi::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        Klasifikasi::find($id)->delete();
        return response()->json(['success' => 'Klasifikasi deleted successfully.']);
    }
}
