<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = ['nombre','codigo','descripcion'];

    public function docentes() { return $this->belongsToMany(Docente::class, 'curso_docente'); }

    // ¡Clave!: sin ->with('user') aquí, para que withCount('alumnos') funcione
    public function alumnos() { return $this->belongsToMany(Alumno::class, 'curso_alumno'); }
}
