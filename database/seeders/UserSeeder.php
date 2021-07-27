<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name' => 'MegaFundas',
            'address' => 'Zona Villa Corina',
            'phone' => 69658908
        ]);

        User::create([
            'name' => 'Augusto Carvalho',
            'phone' => 69658908,
            'profile' => 'ADMIN',
            'status' => 'ACTIVE',
            'email' => 'auguss24@gmail.com',
            'password' => Hash::make('password')
        ]);

        User::create([
            'name' => 'Mariana Elba',
            'phone' => 76885027,
            'profile' => 'EMPLOYEE',
            'status' => 'ACTIVE',
            'email' => 'mari@gmail.com',
            'password' => Hash::make('password')
        ]);
    }
}
