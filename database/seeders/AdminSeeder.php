<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@sipena.com'], // cek dulu biar nggak dobel
            [
                'name' => 'Admin',
                'password' => Hash::make('11221017'),
                'role' => 'admin',
            ]
        );
    }
}
