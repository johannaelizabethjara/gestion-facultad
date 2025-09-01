<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','especialidad','telefono'];

    public function user() { return $this->belongsTo(User::class); }

    public function cursos() { return $this->belongsToMany(Curso::class, 'curso_docente'); }

    // Importante: devolvemos un Builder para listar todos los alumnos de los cursos del docente
    public function alumnos()
    {
        return Alumno::whereHas('cursos.docentes', function ($q) {
            $q->where('docente_id', $this->id);
        });
    }
}
