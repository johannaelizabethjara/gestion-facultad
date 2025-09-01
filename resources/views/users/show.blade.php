@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('users.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Volver a la lista
            </a>
            
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Perfil de Alumno</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <!-- Foto de perfil -->
                            @if($user->photo_path)
                                <img src="{{ Storage::url($user->photo_path) }}" 
                                     alt="Foto de perfil" 
                                     class="img-fluid rounded-circle mb-3" 
                                     style="max-width: 200px; height: 200px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3" 
                                     style="width: 200px; height: 200px;">
                                    <span class="text-white" style="font-size: 4rem;">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <h3>{{ $user->name }}</h3>
                            <p class="text-muted">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-8">
                            <!-- Información de contacto -->
                            <div class="mb-3">
                                <strong><i class="fas fa-phone me-2"></i> Teléfono:</strong>
                                @if($user->phone)
                                    <a href="https://wa.me/{{ $user->phone }}" target="_blank" class="text-decoration-none">
                                        {{ $user->phone }}
                                    </a>
                                @else
                                    <span>No proporcionado</span>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-globe me-2"></i> URL Profesional:</strong>
                                @if($user->professional_url)
                                    <a href="{{ $user->professional_url }}" target="_blank" class="text-decoration-none">
                                        {{ $user->professional_url }}
                                    </a>
                                @else
                                    <span>No proporcionada</span>
                                @endif
                            </div>
                            
                            <!-- Botones de acción -->
                            <div class="mt-4">
                                <!-- Botón para eliminar alumno -->
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="fas fa-trash-alt me-1"></i> Eliminar Alumno
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar a {{ $user->name }}? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('users.destroy', $user) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Script para abrir WhatsApp al hacer clic en el teléfono
    document.addEventListener('DOMContentLoaded', function() {
        const phoneLink = document.querySelector('a[href^="https://wa.me/"]');
        if (phoneLink) {
            phoneLink.addEventListener('click', function(e) {
                // Aquí puedes agregar tracking o lógica adicional si es necesario
                console.log('Abriendo WhatsApp para contactar al alumno');
            });
        }
    });
</script>
@endsection