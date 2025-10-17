<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy untuk pengelolaan Announcement.
 */
class AnnouncementPolicy
{
    use HandlesAuthorization;

    /**
     * Prioritas hak super admin.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('super_admin') ? true : null;
    }

    /**
     * Izin melihat daftar pengumuman.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('announcements.viewAny');
    }

    /**
     * Izin melihat detail pengumuman.
     */
    public function view(User $user, Announcement $announcement): bool
    {
        return $user->can('announcements.view');
    }

    /**
     * Izin membuat pengumuman.
     */
    public function create(User $user): bool
    {
        return $user->can('announcements.create');
    }

    /**
     * Izin memperbarui pengumuman.
     */
    public function update(User $user, Announcement $announcement): bool
    {
        return $user->can('announcements.update');
    }

    /**
     * Izin menghapus pengumuman.
     */
    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->can('announcements.delete');
    }
}
