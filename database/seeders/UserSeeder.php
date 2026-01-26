<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Anwar Fauzi',
            'email' => '21990155-1',
            'password' => bcrypt('@prima.281'),
        ]);
        $admin->assignRole('admin');
    }
}