<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Verificar autenticación
        if (!Auth::check()) {
            abort(403, 'Debe iniciar sesión para acceder a esta página.');
        }

        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        // Verificar autenticación
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para realizar esta acción.');
        }

        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'professional_url' => ['nullable', 'url', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'], // 2MB máximo
        ]);

        try {
            // Actualizar foto si se proporciona
            if ($request->hasFile('photo')) {
                // Eliminar foto anterior si existe
                if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                    Storage::disk('public')->delete($user->photo_path);
                }
                
                $photoPath = $request->file('photo')->store('profile-photos', 'public');
                $user->photo_path = $photoPath;
            }

            $user->fill([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'professional_url' => $request->professional_url,
            ]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            return redirect()->route('profile.edit')->with('status', 'Perfil actualizado correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('profile.edit')
                           ->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Verificar autenticación
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para realizar esta acción.');
        }

        $user = $request->user();

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        try {
            // Eliminar foto de perfil si existe
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            Auth::logout();
            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('status', 'Cuenta eliminada correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('profile.edit')
                           ->with('error', 'Error al eliminar la cuenta: ' . $e->getMessage());
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        // Verificar autenticación
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para realizar esta acción.');
        }

        try {
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->route('profile.edit')->with('status', 'Contraseña actualizada correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('profile.edit')
                           ->with('error', 'Error al actualizar la contraseña: ' . $e->getMessage());
        }
    }

    /**
     * Show admin profile management (solo para administradores)
     */
    public function adminIndex(): View
    {
        // Verificar que el usuario sea administrador
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Acceso restringido. Solo los administradores pueden acceder a esta función.');
        }

        $users = User::orderBy('name')->get();
        return view('profile.admin-index', compact('users'));
    }

    /**
     * Admin delete user (solo para administradores)
     */
    public function adminDestroy(User $user): RedirectResponse
    {
        // Verificar que el usuario sea administrador
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Acceso restringido. Solo los administradores pueden realizar esta acción.');
        }

        // No permitir que un administrador se elimine a sí mismo
        if ($user->id === Auth::id()) {
            return redirect()->route('profile.admin-index')
                           ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        try {
            // Eliminar foto de perfil si existe
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            $userName = $user->name;
            $user->delete();

            return redirect()->route('profile.admin-index')
                           ->with('success', "Usuario {$userName} eliminado correctamente.");

        } catch (\Exception $e) {
            return redirect()->route('profile.admin-index')
                           ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}