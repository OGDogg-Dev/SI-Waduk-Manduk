<?php

namespace App\Policies;

use App\Models\Closure;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy untuk pengelolaan Closure.
 */
class ClosurePolicy
{
    use HandlesAuthorization;

    /**
     * Otomatis izinkan super admin.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('super_admin') ? true : null;
    }

    /**
     * Izin melihat daftar closure.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('closures.viewAny');
    }

    /**
     * Izin melihat detail closure.
     */
    public function view(User $user, Closure $closure): bool
    {
        return $user->can('closures.view');
    }

    /**
     * Izin membuat closure baru.
     */
    public function create(User $user): bool
    {
        return $user->can('closures.create');
    }

    /**
     * Izin memperbarui closure.
     */
    public function update(User $user, Closure $closure): bool
    {
        return $user->can('closures.update');
    }

    /**
     * Izin menghapus closure.
     */
    public function delete(User $user, Closure $closure): bool
    {
        return $user->can('closures.delete');
    }
}
