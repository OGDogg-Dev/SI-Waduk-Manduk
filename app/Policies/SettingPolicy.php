<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy untuk pengelolaan Setting.
 */
class SettingPolicy
{
    use HandlesAuthorization;

    /**
     * Beri akses penuh kepada super admin.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('super_admin') ? true : null;
    }

    /**
     * Izin melihat daftar pengaturan.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('settings.viewAny');
    }

    /**
     * Izin melihat detail pengaturan.
     */
    public function view(User $user, Setting $setting): bool
    {
        return $user->can('settings.view');
    }

    /**
     * Izin membuat pengaturan baru.
     */
    public function create(User $user): bool
    {
        return $user->can('settings.create');
    }

    /**
     * Izin memperbarui pengaturan.
     */
    public function update(User $user, Setting $setting): bool
    {
        return $user->can('settings.update');
    }

    /**
     * Izin menghapus pengaturan.
     */
    public function delete(User $user, Setting $setting): bool
    {
        return $user->can('settings.delete');
    }
}
