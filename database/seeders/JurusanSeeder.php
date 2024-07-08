<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id'            => '1',
                'name'          => 'Kimia Industri',
                'short_name'    => 'KI',
            ],
            [
                'id'            => '2',
                'name'          => 'Teknik Kendaraan Ringan',
                'short_name'    => 'TKR',
            ],
            [
                'id'            => '3',
                'name'          => 'Teknik Sepeda Motor',
                'short_name'    => 'TSM',
            ],
            [
                'id'            => '4',
                'name'          => 'Teknik dan Bisnis Sepeda Motor',
                'short_name'    => 'TBKR',
            ],
            [
                'id'            => '5',
                'name'          => 'Teknik Komputer dan Jaringan',
                'short_name'    => 'TKJ',
            ],
            [
                'id'            => '6',
                'name'          => 'Rekayasa Perangkat Lunak',
                'short_name'    => 'RPL',
            ],
            [
                'id'            => '7',
                'name'          => 'Teknik Kendaraan Ringan Otomotif',
                'short_name'    => 'TKRO',
            ],
            [
                'id'            => '8',
                'name'          => 'Teknik dan Bisnis Sepeda Motor',
                'short_name'    => 'TBSM',
            ],
            [
                'id'            => '9',
                'name'          => 'Teknik Bodi Otomotif',
                'short_name'    => 'TBO',
            ]
        ];
        Jurusan::insert($data);
    }
}
