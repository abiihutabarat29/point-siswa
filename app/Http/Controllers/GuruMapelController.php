<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel;
use App\Models\Semester;
use App\Models\Tapel;

class GuruMapelController extends Controller
{
    public function index($id)
    {
        $tapel = Tapel::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();

        $mapelData = GuruMapel::with('mapel')
            ->select('mapel_id', 'rombel_id')
            ->where('tapel_id', $tapel->id)
            ->where('semester_id', $semester->id)
            ->where('guru_id', $id)
            ->get();

        $groupedMapel = $mapelData->groupBy('mapel_id');

        $formattedMapel = $groupedMapel->map(function ($items) {
            $mapel = $items->first()->mapel;
            $rombel_ids = $items->pluck('rombel_id')->toArray();
            return [
                'mapel_id' => $mapel->id,
                'rombel_id' => $rombel_ids,
                'mapel' => $mapel
            ];
        });

        return response()->json($formattedMapel);
    }
}
