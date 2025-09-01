@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Lista de Alumnos</h3>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @php
                        // Asegurar que la variable exista
                        $alumnos = $alumnos ?? collect([]);
                    @endphp

                    @if($alumnos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>URL Profesional</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alumnos as $alumno)
                                        <tr>
                                            <td>
                                                @if($alumno->photo_path)
                                                    <img src="{{ Storage::url($alumno->photo_path) }}" 
                                                         alt="Foto de perfil" 
                                                         class="rounded-circle" 
                                                         width="50" 
                                                         height="50">
                                                @else
                                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <span class="text-white">{{ substr($alumno->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $alumno->name }}</td>
                                            <td>{{ $alumno->email }}</td>
                                            <td>{{ $alumno->phone ?? 'No especificado' }}</td>
                                            <td>
                                                @if($alumno->professional_url)
                                                    <a href="{{ $alumno->professional_url }}" target="_blank">
                                                        {{ Str::limit($alumno->professional_url, 30) }}
                                                    </a>
                                                @else
                                                    No especificado
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('users.show', $alumno) }}" 
                                                   class="btn btn-info btn-sm">
                                                    Ver Perfil
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No hay alumnos registrados.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection