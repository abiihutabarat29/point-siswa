<?php

namespace Database\Seeders;

use App\Imports\ImportGuru;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $filePath = public_path('data/format-guru.csv');
        $excelData = Excel::toArray(new ImportGuru, $filePath);

        foreach ($excelData[0] as $row) {
            $guru = Guru::create([
                'name'          => $row['name'],
                'tmp_lahir'     => $row['tmp_lahir'],
                'tgl_lahir'     => $row['tgl_lahir'],
                'gender'        => $row['gender'],
                'nip'           => $row['nip'],
                'status'        => $row['status'],
            ]);

            User::create(
                [
                    'id_card'   => $guru->nip,
                    'name'      => $guru->name,
                    'password'  => "12345678",
                    'role_id'   => 3,
                ]
            );
        }
    }
}
