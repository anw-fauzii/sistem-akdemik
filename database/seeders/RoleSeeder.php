<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'siswa_tk',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'siswa_sd',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'guru_tk',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'guru_sd',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'puskesmas',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'bendahara',
            'guard_name' => 'web'
        ]);
    }
}