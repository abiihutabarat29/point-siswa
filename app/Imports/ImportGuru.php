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
            'name'          => $row['name'],
            'tmp_lahir'     => $row['tmp_lahir'],
            'tgl_lahir'     => $row['tgl_lahir'],
            'gender'        => $row['gender'],
            'nip'           => $row['nip'],
            'status'        => $row['status'],
        ]);
    }
}
