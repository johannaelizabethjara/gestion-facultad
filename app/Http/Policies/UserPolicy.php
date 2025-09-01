public function viewAny(User $user): bool
{
    return $user->is_admin; // Debe devolver true para administradores
}


public function viewAny(User $user): bool
{
    return $user->is_admin == 1; // Verifica explícitamente el valor 1
}

public function viewAny(User $user): bool
{
    // return $user->is_admin == 1;  // ← Comenta esta línea
    return true;  // ← Permite acceso a todos temporalmente
}