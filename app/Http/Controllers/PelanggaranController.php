<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class PelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $menu = 'Jenis Pelanggaran';
        if ($request->ajax()) {
            $data = Pelanggaran::latest();
            return Datatables::of($data)
                ->addColumn('bobot', function ($data) {
                    return '<center>' . $data->bobot . '<center>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <center><div class="col mb-0">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <button type="button" class="dropdown-item edit"
                                            data-bs-toggle="offcanvas" data-bs-target="#ajaxModel"
                                            aria-controls="ajaxModel"
                                            data-id="' . $row->id . '">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </button>
                                        <button type="button" class="dropdown-item delete" data-bs-toggle="modal"
                                        data-bs-target="#ajaxModelHps" data-id="' . $row->id . '">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div></center>';
                })
                ->rawColumns(['action', 'bobot'])
                ->make(true);
        }

        return view('pelanggaran.index', compact('menu'));
    }

    public function store(Request $request)
    {
        $message = array(
            'name.required'         => 'Nama Kelas harus diisi.',
            'bobot.required'        => 'Bobot harus diisi.',
        );

        $validator = Validator::make($request->all(), [
            'name'              => 'required',
            'bobot'             => 'required',
        ], $message);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        Pelanggaran::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'name'          => $request->name,
                'bobot'         => $request->bobot,
            ]
        );

        return response()->json(['success' => 'Pelanggaran saved successfully.']);
    }

    public function edit($id)
    {
        $data = Pelanggaran::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        Pelanggaran::find($id)->delete();
        return response()->json(['success' => 'Pelanggaran deleted successfully.']);
    }
}
