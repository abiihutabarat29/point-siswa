<?php

namespace App\Imports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportGuru implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Guru([
            'name'          => $row['name'] ?? null,
            'tmp_lahir'     => $row['tmp_lahir'] ?? null,
            'tgl_lahir'     => $row['tgl_lahir'] ?? null,
            'gender'        => $row['gender'] ?? null,
            'nip'           => $row['nip'] ?? null,
            'status'        => $row['status'] ?? null,
        ]);
    }
}
