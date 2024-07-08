<?php

namespace Database\Seeders;

use App\Models\MapelKelompok;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MapelKelompokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                "id" => "1",
                "name" => "Kelompok A",
                "kode" => "A",
                "kategori" => "Wajib",
                "parent" => "0",
            ],
            [
                "id" => "2",
                "name" => "Kelompok B",
                "kode" => "B",
                "kategori" => "Wajib",
                "parent" => "0",
            ],
            [
                "id" => "3",
                "name" => "Kelompok C",
                "kode" => "C",
                "kategori" => "Peminatan",
                "parent" => "0",
            ],
            [
                "id" => "4",
                "name" => "C1. Dasar Bidang Keahlian",
                "kode" => "C1",
                "kategori" => "Peminatan",
                "parent" => "3",
            ],
            [
                "id" => "5",
                "name" => "C2. Dasar Program Keahlian",
                "kode" => "C2",
                "kategori" => "Peminatan",
                "parent" => "3",
            ],
            [
                "id" => "6",
                "name" => "C3. Paket Keahlian",
                "kode" => "C3",
                "kategori" => "Peminatan",
                "parent" => "3",
            ]
        ];

        foreach ($datas as $data) {
            MapelKelompok::create($data);
        }
    }
}
