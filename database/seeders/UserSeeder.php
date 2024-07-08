<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{

    public function run()
    {
        $data = [
            [
                'name'     => 'Admin',
                'email'    => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role_id'  => 1,
                'id_card'  => $this->generateRandomNumber(18)
            ],
            [
                'name'     => 'Operator',
                'email'    => 'operator@gmail.com',
                'password' => Hash::make('password'),
                'role_id'  => 2,
                'id_card'  => $this->generateRandomNumber(18)
            ]
        ];

        User::insert($data);
    }

    private function generateRandomNumber($length)
    {
        $randomNumber = '';

        for ($i = 0; $i < $length; $i++) {
            $randomNumber .= mt_rand(0, 9);
        }

        return $randomNumber;
    }
}
