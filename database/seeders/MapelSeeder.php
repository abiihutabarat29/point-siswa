<?php

namespace Database\Seeders;

use App\Models\Mapel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(public_path('data/mapel.json'));
        $datas = json_decode($json, true);

        foreach ($datas as $data) {
            Mapel::create([
                'id' => $data['id'],
                'name' => $data['name'],
                'kode' => $data['kode'],
                'kelompok_id' => $data['kelompok_id'],
                'status' => $data['status'],
            ]);
        }
    }
}
