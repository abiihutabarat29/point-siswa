<?php

namespace App\Imports;

use App\Models\Mapel;
use App\Models\MapelKelompok;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportMapel implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $kelompok = MapelKelompok::where('kode', $row['kelompok'])->first();

        if ($kelompok) {
            return new Mapel([
                'name'          => $row['name'],
                'kode'          => $row['kode'],
                'kelompok_id'   => $kelompok->id,
                'status'        => 1
            ]);
        }

        return null;
    }
}
