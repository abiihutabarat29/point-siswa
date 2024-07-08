<?php

namespace Database\Seeders;

use App\Models\Tapel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'tahun'     => '2021/2022',
                'status'     => null,
            ],
            [
                'tahun'     => '2022/2023',
                'status'     => null,
            ],
            [
                'tahun'     => '2023/2024',
                'status'     => null,
            ],
            [
                'tahun'     => '2024/2025',
                'status'     => null,
            ],
        ];
        Tapel::insert($data);
    }
}
