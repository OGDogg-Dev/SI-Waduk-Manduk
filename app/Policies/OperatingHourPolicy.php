<?php

namespace App\Policies;

use App\Models\OperatingHour;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy untuk hak akses OperatingHour.
 */
class OperatingHourPolicy
{
    use HandlesAuthorization;

    /**
     * Izinkan super admin di semua aksi.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('super_admin') ? true : null;
    }

    /**
     * Validasi izin melihat daftar.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('operating_hours.viewAny');
    }

    /**
     * Validasi izin melihat detail.
     */
    public function view(User $user, OperatingHour $operatingHour): bool
    {
        return $user->can('operating_hours.view');
    }

    /**
     * Validasi izin membuat data.
     */
    public function create(User $user): bool
    {
        return $user->can('operating_hours.create');
    }

    /**
     * Validasi izin memperbarui data.
     */
    public function update(User $user, OperatingHour $operatingHour): bool
    {
        return $user->can('operating_hours.update');
    }

    /**
     * Validasi izin menghapus data.
     */
    public function delete(User $user, OperatingHour $operatingHour): bool
    {
        return $user->can('operating_hours.delete');
    }
}
