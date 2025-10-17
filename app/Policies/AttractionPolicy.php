<?php

namespace App\Policies;

use App\Models\Attraction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy untuk mengatur hak akses data Attraction.
 */
class AttractionPolicy
{
    use HandlesAuthorization;

    /**
     * Izinkan super admin secara global.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('super_admin') ? true : null;
    }

    /**
     * Cek izin melihat daftar attraction.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('attractions.viewAny');
    }

    /**
     * Cek izin melihat detail attraction.
     */
    public function view(User $user, Attraction $attraction): bool
    {
        return $user->can('attractions.view');
    }

    /**
     * Cek izin membuat attraction baru.
     */
    public function create(User $user): bool
    {
        return $user->can('attractions.create');
    }

    /**
     * Cek izin memperbarui attraction.
     */
    public function update(User $user, Attraction $attraction): bool
    {
        return $user->can('attractions.update');
    }

    /**
     * Cek izin menghapus attraction.
     */
    public function delete(User $user, Attraction $attraction): bool
    {
        return $user->can('attractions.delete');
    }
}
