<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::create([
            'name' => 'Ouedraogo',
            'prenom' => 'Salam Jean-Louis',
            'contact' => '0798278981',
            'adresse' => 'Abidjan',
            'role' => 'admin',
            'email_verified_at' => now(),
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Salam@2026'),
        ]);
       
    }
}
