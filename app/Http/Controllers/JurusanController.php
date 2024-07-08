<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class JurusanController extends Controller
{
    public function index(Request $request)
    {
        $menu = 'Jurusan';
        if ($request->ajax()) {
            $data = Jurusan::latest();
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

        return view('jurusan.index', compact('menu'));
    }

    public function store(Request $request)
    {
        $message = array(
            'name.required'         => 'Nama Jurusan harus diisi.',
            'short_name.required'   => 'Singkatan harus diisi.',
        );

        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'short_name'    => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        Jurusan::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'name'          => $request->name,
                'short_name'    => $request->short_name,
            ]
        );

        return response()->json(['success' => 'Jurusan saved successfully.']);
    }

    public function edit($id)
    {
        $data = Jurusan::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        Jurusan::find($id)->delete();
        return response()->json(['success' => 'Jurusan deleted successfully.']);
    }
}
