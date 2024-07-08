<?php

namespace App\Http\Controllers;

use App\Models\Klasifikasi;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $menu = 'Surat Masuk';
        $klasifikasi = Klasifikasi::all();
        if ($request->ajax()) {
            $data = SuratMasuk::latest();
            return Datatables::of($data)
                ->addColumn('kode', function ($data) {
                    return $data->klasifikasi->kode;
                })
                ->addColumn('tgl_surat', function ($data) {
                    return Carbon::parse($data->tgl_surat)->isoFormat('D MMMM Y');
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
                            <button type="button" class="dropdown-item" onclick="window.location=\'' . url('surat-masuk/download/' . $row->id) . '\'">
                            <i class="bx bx-download me-1"></i> Download
                            </button>
                            <button type="button" class="dropdown-item review" data-bs-toggle="modal" data-id="' . $row->id . '">
                                <i class="bx bx-file-find me-1"></i> Review
                            </button>
                        </div>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('surat-masuk.index', compact('menu', 'klasifikasi'));
    }

    public function store(Request $request)
    {
        $message = array(
            'nomor.required'             => 'Nomor Surat harus diisi.',
            'sifat.required'             => 'Sifat Surat harus dipilih.',
            'perihal.required'           => 'Perihal harus diisi.',
            'perihal.max'                => 'Perihal maksimal 255 karakter.',
            'asal.required'              => 'Asal Surat harus diisi.',
            'klasifikasi_id.required'    => 'Klasifikasi Surat harus dipilih.',
            'tgl_surat.required'         => 'Tanggal Surat harus diisi.',
            'file_surat.required'        => 'File Surat harus diupload.',
            'file_surat.*.mimes'         => 'Tipe file yang diunggah harus pdf.',
            'file_surat.*.max'           => 'Ukuran file tidak boleh lebih dari 2 MB.',
        );

        $ruleFile = $request->hidden_id ? 'mimes:pdf|max:2048' : 'required|mimes:pdf|max:2048';

        $validator = Validator::make($request->all(), [
            'klasifikasi_id'      => 'required',
            'nomor'      => 'required',
            'sifat'      => 'required',
            'perihal'    => 'required|max:255',
            'asal'       => 'required',
            'file_surat' => $ruleFile,
            'tgl_surat'  => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($request->hasFile('file_surat')) {
            $surat = $request->file('file_surat');
            $fileSurat = time() . '-' . $surat->getClientOriginalName();
            $surat->storeAs('public/surat-masuk', $fileSurat);
            if ($request->hidden_id) {
                $oldFile = SuratMasuk::find($request->hidden_id);
                Storage::delete('public/surat-masuk/' . $oldFile->file_surat);
            }
        } elseif ($request->hidden_id) {
            $oldFile = SuratMasuk::find($request->hidden_id);
            $fileSurat = $oldFile->file_surat;
        } else {
            $fileSurat = null;
        }

        SuratMasuk::updateOrCreate(
            [
                'id' => $request->hidden_id
            ],
            [
                'klasifikasi_id'  => $request->klasifikasi_id,
                'nomor'           => $request->nomor,
                'sifat'           => $request->sifat,
                'perihal'         => $request->perihal,
                'asal'            => $request->asal,
                'file_surat'      => $fileSurat,
                'tgl_surat'       => $request->tgl_surat,
            ]
        );

        return response()->json(['success' => 'Surat Masuk saved successfully.']);
    }

    public function edit($id)
    {
        $data = SuratMasuk::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = SuratMasuk::find($id);
        if ($data) {
            Storage::delete('public/surat-masuk/' . $data->file_surat);
            $data->delete();
            return response()->json(['success' => 'Surat Masuk deleted successfully.']);
        }
        return response()->json(['error' => 'Surat Masuk tidak ditemukan.'], 404);
    }

    public function download($id)
    {
        $data = SuratMasuk::findOrFail($id);

        $filePath = storage_path('app/public/surat-masuk/' . $data->file_surat);
        $fileName = $data->file;
        if (file_exists($filePath)) {

            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            return response()->download($filePath, $fileName, $headers);
        }

        abort(404, 'File not found');
    }

    public function review($id)
    {
        $data = SuratMasuk::where("id", $id)->get();
        if ($data) {
            return response()->json($data);
        }
        return response()->json(['error' => 'Surat Masuk tidak ditemukan.'], 404);
    }
}
