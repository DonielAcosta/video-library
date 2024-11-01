<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'doniel',
            'email' => 'doni@gmail.com',
            'password' => bcrypt('123456789'),
            'role' => 'admin', // Asignar el rol adecuado
        ]);
        User::create([
            'name' => 'doniel',
            'email' => 'doni1@gmail.com',
            'password' => bcrypt('123456789'),
            'role' => 'user', // Asignar el rol adecuado
        ]);
    }
}
