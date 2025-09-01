<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','matricula','telefono'];

    public function user() { return $this->belongsTo(User::class); }

    public function cursos() { return $this->belongsToMany(Curso::class, 'curso_alumno'); }
}
