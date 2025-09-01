<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Docente;
use App\Models\Alumno;
use App\Models\Curso;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roleDocente = Role::where('nombre', 'docente')->first();
        $roleAlumno  = Role::where('nombre', 'alumno')->first();

        // Docente
        $docenteUser = User::updateOrCreate(
            ['email' => 'docente@test.com'],
            [
                'name' => 'Prof. García',
                'password' => Hash::make('password'),
                'role_id' => $roleDocente->id
            ]
        );

        $docente = Docente::updateOrCreate(
            ['user_id' => $docenteUser->id],
            ['especialidad' => 'Programación', 'telefono' => '3515550000']
        );

        // Alumnos
        $alumnoUser1 = User::updateOrCreate(
            ['email' => 'alumno1@test.com'],
            [
                'name' => 'Ana López',
                'password' => Hash::make('password'),
                'role_id' => $roleAlumno->id
            ]
        );
        $alumno1 = Alumno::updateOrCreate(
            ['user_id' => $alumnoUser1->id],
            ['matricula' => 'A001','telefono'=>'3511111111']
        );

        $alumnoUser2 = User::updateOrCreate(
            ['email' => 'alumno2@test.com'],
            [
                'name' => 'Juan Pérez',
                'password' => Hash::make('password'),
                'role_id' => $roleAlumno->id
            ]
        );
        $alumno2 = Alumno::updateOrCreate(
            ['user_id' => $alumnoUser2->id],
            ['matricula' => 'A002','telefono'=>'3512222222']
        );

        // Cursos
        $curso1 = Curso::updateOrCreate(
            ['codigo' => 'MAT101'],
            ['nombre' => 'Matemáticas Avanzadas','descripcion' => 'Curso de matemáticas para ingeniería']
        );
        $curso2 = Curso::updateOrCreate(
            ['codigo' => 'PROG202'],
            ['nombre' => 'Programación Web','descripcion' => 'Desarrollo web con Laravel']
        );

        // Relaciones
        $docente->cursos()->sync([$curso1->id, $curso2->id]);      // docente imparte dos cursos
        $curso1->alumnos()->sync([$alumno1->id, $alumno2->id]);    // ambos alumnos en MAT101
        $curso2->alumnos()->sync([$alumno1->id]);                  // Ana en PROG202
    }
}
