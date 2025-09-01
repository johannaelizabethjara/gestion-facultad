<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the alumnos (students).
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Verificar autenticación
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder a esta página.');
        }

        // Verificar que el usuario sea administrador
        if (!Auth::user()->is_admin) {
            abort(403, 'Acceso restringido. Solo los administradores pueden ver la lista de alumnos.');
        }

        try {
            // Obtener solo los alumnos (donde is_admin = false) con paginación
            $alumnos = User::where('is_admin', false)
                          ->orderBy('name')
                          ->paginate(10); // Paginación para mejor rendimiento

            return view('users.index', compact('alumnos'));
            
        } catch (\Exception $e) {
            // Log del error y redirección amigable
            \Log::error('Error al cargar lista de alumnos: ' . $e->getMessage());
            return redirect()->route('dashboard')
                           ->with('error', 'Ocurrió un error al cargar la lista de alumnos. Por favor, intente nuevamente.');
        }
    }

    /**
     * Display the specified alumno.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(User $user)
    {
        // Verificar autenticación
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder a esta página.');
        }

        // Verificar que el usuario sea administrador
        if (!Auth::user()->is_admin) {
            abort(403, 'Acceso restringido. Solo los administradores pueden ver perfiles de alumnos.');
        }

        // Asegurarse de que solo se puedan ver alumnos (no administradores)
        if ($user->is_admin) {
            abort(404, 'El perfil solicitado no existe o no está disponible.');
        }

        try {
            // Cargar relaciones si es necesario (ej: cursos, calificaciones, etc.)
            // $user->load('cursos', 'calificaciones');
            
            return view('users.show', compact('user'));
            
        } catch (\Exception $e) {
            // Log del error y redirección amigable
            \Log::error('Error al cargar perfil de alumno ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->route('users.index')
                           ->with('error', 'Ocurrió un error al cargar el perfil del alumno. Por favor, intente nuevamente.');
        }
    }

    /**
     * Remove the specified alumno from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Verificar autenticación
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para realizar esta acción.');
        }

        // Verificar que el usuario sea administrador
        if (!Auth::user()->is_admin) {
            abort(403, 'Acceso restringido. Solo los administradores pueden eliminar alumnos.');
        }

        // Asegurarse de que solo se puedan eliminar alumnos (no administradores)
        if ($user->is_admin) {
            abort(404, 'No se puede eliminar el perfil solicitado.');
        }

        try {
            // Eliminar el usuario
            $user->delete();
            
            return redirect()->route('users.index')
                ->with('success', 'Alumno eliminado correctamente');
                
        } catch (\Exception $e) {
            // Log del error y redirección amigable
            \Log::error('Error al eliminar alumno ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->route('users.index')
                           ->with('error', 'Ocurrió un error al eliminar el alumno. Por favor, intente nuevamente.');
        }
    }

    /**
     * Show the form for searching alumnos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        // Verificar autenticación y permisos
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Acceso restringido. Solo los administradores pueden buscar alumnos.');
        }

        try {
            $query = User::where('is_admin', false);
            
            // Búsqueda por nombre
            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            
            // Búsqueda por email
            if ($request->has('email')) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }

            $alumnos = $query->orderBy('name')->paginate(10);
            
            return view('users.index', compact('alumnos'));
            
        } catch (\Exception $e) {
            \Log::error('Error en búsqueda de alumnos: ' . $e->getMessage());
            return redirect()->route('users.index')
                           ->with('error', 'Ocurrió un error en la búsqueda. Por favor, intente nuevamente.');
        }
    }

    /**
     * Export list of alumnos to CSV.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export()
    {
        // Verificar autenticación y permisos
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Acceso restringido. Solo los administradores pueden exportar datos.');
        }

        try {
            $alumnos = User::where('is_admin', false)
                          ->orderBy('name')
                          ->get();

            $filename = "alumnos_" . date('Y-m-d') . ".csv";
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            return response()->stream(function() use ($alumnos) {
                $handle = fopen('php://output', 'w');
                
                // Encabezados CSV
                fputcsv($handle, ['ID', 'Nombre', 'Email', 'Teléfono', 'URL Profesional', 'Fecha Registro']);
                
                // Datos
                foreach ($alumnos as $alumno) {
                    fputcsv($handle, [
                        $alumno->id,
                        $alumno->name,
                        $alumno->email,
                        $alumno->phone ?? 'No especificado',
                        $alumno->professional_url ?? 'No especificado',
                        $alumno->created_at->format('d/m/Y')
                    ]);
                }
                
                fclose($handle);
            }, 200, $headers);
            
        } catch (\Exception $e) {
            \Log::error('Error al exportar alumnos: ' . $e->getMessage());
            return redirect()->route('users.index')
                           ->with('error', 'Ocurrió un error al exportar los datos. Por favor, intente nuevamente.');
        }
    }
}