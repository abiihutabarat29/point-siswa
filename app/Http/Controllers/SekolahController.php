<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class SekolahController extends Controller
{
    public function index(Request $request)
    {
        $menu = 'Sekolah';
        if ($request->ajax()) {
            $data = Sekolah::latest();
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

        return view('sekolah.index', compact('menu'));
    }

    public function store(Request $request)
    {
        $message = array(
            'name.required'             => 'Tahun harus diisi.',
        );

        $validator = Validator::make($request->all(), [
            'name'              => 'required',

        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        Sekolah::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'npsn'      => $request->npsn,
                'name'      => $request->name,
                'bentuk'    => $request->bentuk,
                'status'    => $request->status,
                'alamat'    => $request->alamat,
                'kepsek'    => $request->kepsek,
            ]
        );

        return response()->json(['success' => 'Sekolah saved successfully.']);
    }

    public function edit($id)
    {
        $data = Sekolah::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        Sekolah::find($id)->delete();
        return response()->json(['success' => 'Sekolah deleted successfully.']);
    }
}
