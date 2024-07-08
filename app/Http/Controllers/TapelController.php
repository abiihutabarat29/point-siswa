<?php

namespace App\Http\Controllers;

use App\Models\HariEfektif;
use App\Models\Semester;
use App\Models\Tapel;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class TapelController extends Controller
{
    public function index(Request $request)
    {
        $menu = 'Tahun Pelajaran';
        $semester = Semester::all();
        $tapel = Tapel::where('status', 1)->first();
        $sem = Semester::where('status', 1)->first();
        $hariEfektif = null;
        if ($tapel && $sem) {
            $hariEfektif = HariEfektif::where('tapel_id', $tapel->id)
                ->where('semester_id', $sem->id)
                ->first();
        }

        if ($request->ajax()) {
            $data = Tapel::latest();
            return Datatables::of($data)
                ->addColumn('status', function ($data) {
                    if ($data->status == 1) {
                        $status = '<div class="col mb-0">
                                <span class="text-success">
                                    <i class="bx bx-check-double"></i> AKTIF
                                </span>
                            </div>';
                    } else {
                        $status = '<div class="col mb-0">
                                <a href="' . route('tapel.status', $data->id) . '" type="button"
                                    class="btn btn-sm btn-primary">
                                    <span class="me-1"></span> AKTIFKAN
                                </a>
                            </div>';
                    }

                    return $status;
                })
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
                ->rawColumns(['action', 'status', 'semester'])
                ->make(true);
        }

        return view('tapel.index', compact('menu', 'semester', 'hariEfektif'));
    }

    public function store(Request $request)
    {
        $message = array(
            'tahun.required'    => 'Tahun harus diisi.',
        );

        $validator = Validator::make($request->all(), [
            'tahun'     => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        Tapel::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'tahun'     => $request->tahun,
            ]
        );

        return response()->json(['success' => 'Tahun Pelajaran saved successfully.']);
    }

    public function edit($id)
    {
        $data = Tapel::find($id);
        return response()->json($data);
    }

    public function status($id)
    {
        $old = Tapel::where('status', 1)->first();
        if (isset($old)) {
            $old->status = 0;
            $old->save();
        }
        $data = Tapel::find($id);
        $data->status = 1;
        $data->save();

        $semester = Semester::where("id", 1)->first();
        if ($semester->status == 0) {
            $semester->status = 1;
            $semester->save();

            $old = Semester::where('id', 2)->first();
            $old->status = 0;
            $old->save();
        }

        return back()->with(['success' => 'Tahun Pelajaran change successfully.']);
    }

    public function destroy($id)
    {
        Tapel::find($id)->delete();
        return response()->json(['success' => 'Tahun Pelajaran deleted successfully.']);
    }

    public function statusSem($id)
    {
        $old = Semester::where('status', 1)->first();
        if (isset($old)) {
            $old->status = 0;
            $old->save();
        }
        $data = Semester::find($id);
        $data->status = 1;
        $data->save();
        return back()->with(['success' => 'Semester change successfully.']);
    }

    public function storeHE(Request $request)
    {
        $tapel = Tapel::where('status', 1)->first();
        if (!$tapel) {
            return redirect()->back()->with('error', 'Tidak ada Tapel aktif.');
        }

        $semester = Semester::where('status', 1)->first();
        if (!$semester) {
            return redirect()->back()->with('error', 'Tidak ada Semester aktif.');
        }

        $hariEfektif = HariEfektif::where('tapel_id', $tapel->id)
            ->where('semester_id', $semester->id)
            ->first();

        if ($hariEfektif) {
            $hariEfektif->update([
                'jumlah' => $request->jumlah,
            ]);
        } else {
            HariEfektif::create([
                'tapel_id' => $tapel->id,
                'semester_id' => $semester->id,
                'jumlah' => $request->jumlah,
            ]);
        }

        return redirect()->back()->with('success', 'Data Hari Efektif berhasil disimpan.');
    }
}
