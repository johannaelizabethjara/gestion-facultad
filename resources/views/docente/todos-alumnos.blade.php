@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-md-12">
    <h2>Todos mis Alumnos</h2>
    <div class="card">
      <div class="card-header">
        <h4>Lista Completa de Alumnos</h4>
      </div>
      <div class="card-body">
        @if($alumnos->count() > 0)
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
              @foreach($alumnos as $alumno)
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
          <p>No tienes alumnos asignados.</p>
        @endif
      </div>
    </div>
    <div class="mt-3">
      <a href="{{ route('docente.dashboard') }}" class="btn btn-secondary">Volver al Dashboard</a>
    </div>
  </div>
</div>
@endsection
