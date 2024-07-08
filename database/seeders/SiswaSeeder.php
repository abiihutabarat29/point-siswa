<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 225; $i++) {
            $gender = $faker->randomElement(['L', 'P']);
            $foto = ($gender == 'L') ? 'male.png' : 'female.png';

            $siswa = Siswa::create([
                'nisn' => $faker->unique()->randomNumber(8),
                'name' => $faker->name,
                'gender' => $gender,
                'tmp_lahir' => $faker->city,
                'tgl_lahir' => $faker->dateTimeBetween('2006-01-01', '2008-12-31')->format('Y-m-d'),
                'foto' => $foto,
                'pendaftaran' => $faker->randomElement(['Siswa Baru', 'Pindahan']),
                'tgl_masuk' => $faker->dateTimeBetween('2023-01-01', '2023-12-31')->format('Y-m-d'),
            ]);

            User::create([
                'id_card' => $siswa->nisn,
                'name' => $siswa->name,
                'password' => Hash::make("12345678"),
                'role_id' => 4,
            ]);
        }
    }
}
