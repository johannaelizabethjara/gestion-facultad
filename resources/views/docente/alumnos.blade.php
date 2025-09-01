@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-md-12">
    <h2>Alumnos del Curso: {{ $curso->nombre }}</h2>
    <div class="card">
      <div class="card-header">
        <h4>Lista de Alumnos</h4>
      </div>
      <div class="card-body">
        @if($curso->alumnos->count() > 0)
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
              <tr>
                <th>Matrícula</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
              </tr>
              </thead>
              <tbody>
              @foreach($curso->alumnos as $alumno)
                <tr>
                  <td>{{ $alumno->matricula }}</td>
                  <td>{{ $alumno->user->name }}</td>
                  <td>{{ $alumno->user->email }}</td>
                  <td>{{ $alumno->telefono }}</td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p>No hay alumnos inscritos en este curso.</p>
        @endif
      </div>
    </div>
    <div class="mt-3">
      <a href="{{ route('docente.dashboard') }}" class="btn btn-secondary">Volver al Dashboard</a>
    </div>
  </div>
</div>
@endsection
