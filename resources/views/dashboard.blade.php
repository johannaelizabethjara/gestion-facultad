@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5>¡Bienvenido! You're logged in!</h5>

                    <!-- Mostrar información del usuario -->
                    <div class="mt-3">
                        <p><strong>Nombre:</strong> {{ Auth::user()->name }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        <p><strong>Rol:</strong> 
                            @if(Auth::user()->is_admin)
                                <span class="badge bg-danger">Administrador/Profesor</span>
                            @else
                                <span class="badge bg-success">Alumno</span>
                            @endif
                        </p>
                    </div>

                    <!-- Mostrar acciones solo para administradores -->
                    @if(Auth::user()->is_admin)
                    <div class="mt-4">
                        <h6>Acciones de Administrador:</h6>
                        <a href="{{ route('users.index') }}" class="btn btn-success mb-2">
                            📋 Ver Lista de Alumnos
                        </a>
                    </div>
                    @endif

                    <!-- Debug para todos -->
                    <div class="mt-3">
                        <h6>Debug:</h6>
                        @if(Auth::user()->is_admin)
                            <a href="/alumnos" class="btn btn-primary btn-sm mb-2">
                                🔗 Ir a /alumnos directamente
                            </a>
                            <br>
                        @endif
                        <small class="text-muted">
                            URL actual: {{ url()->current() }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection