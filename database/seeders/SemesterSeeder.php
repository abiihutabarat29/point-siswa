<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name'     => 'GANJIL',
                'status'     => null,
            ],
            [
                'name'     => 'GENAP',
                'status'     => null,
            ],
        ];
        Semester::insert($data);
    }
}
