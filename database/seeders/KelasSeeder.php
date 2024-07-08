<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name'     => 'X',
            ],
            [
                'name'     => 'XI',
            ],
            [
                'name'     => 'XII',
            ]
        ];
        Kelas::insert($data);
    }
}
