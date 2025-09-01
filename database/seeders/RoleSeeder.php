<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(['nombre' => 'admin']);
        Role::updateOrCreate(['nombre' => 'docente']);
        Role::updateOrCreate(['nombre' => 'alumno']);
    }
}
