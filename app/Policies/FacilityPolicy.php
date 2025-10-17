<?php

namespace App\Policies;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy untuk pengelolaan Facility.
 */
class FacilityPolicy
{
    use HandlesAuthorization;

    /**
     * Dahulukan super admin.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('super_admin') ? true : null;
    }

    /**
     * Akses lihat daftar fasilitas.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('facilities.viewAny');
    }

    /**
     * Akses lihat detail fasilitas.
     */
    public function view(User $user, Facility $facility): bool
    {
        return $user->can('facilities.view');
    }

    /**
     * Akses membuat fasilitas.
     */
    public function create(User $user): bool
    {
        return $user->can('facilities.create');
    }

    /**
     * Akses memperbarui fasilitas.
     */
    public function update(User $user, Facility $facility): bool
    {
        return $user->can('facilities.update');
    }

    /**
     * Akses menghapus fasilitas.
     */
    public function delete(User $user, Facility $facility): bool
    {
        return $user->can('facilities.delete');
    }
}
