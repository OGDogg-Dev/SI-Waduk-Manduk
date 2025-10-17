<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy untuk pengelolaan Event.
 */
class EventPolicy
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
     * Akses melihat daftar event.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('events.viewAny');
    }

    /**
     * Akses melihat detail event.
     */
    public function view(User $user, Event $event): bool
    {
        return $user->can('events.view');
    }

    /**
     * Akses membuat event baru.
     */
    public function create(User $user): bool
    {
        return $user->can('events.create');
    }

    /**
     * Akses memperbarui event.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->can('events.update');
    }

    /**
     * Akses menghapus event.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->can('events.delete');
    }
}
