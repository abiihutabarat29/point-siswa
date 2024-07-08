<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            HariSeeder::class,
            TapelSeeder::class,
            SemesterSeeder::class,
            MapelKelompokSeeder::class,
            MapelSeeder::class,
            JurusanSeeder::class,
            SiswaSeeder::class,
            KelasSeeder::class,
            JabatanSeeder::class,
            RombelSeeder::class,
            GuruSeeder::class,
        ]);
    }
}
