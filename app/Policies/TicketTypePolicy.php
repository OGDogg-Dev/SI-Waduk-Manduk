<?php

namespace App\Policies;

use App\Models\TicketType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy untuk pengelolaan TicketType.
 */
class TicketTypePolicy
{
    use HandlesAuthorization;

    /**
     * Izinkan super admin terlebih dahulu.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('super_admin') ? true : null;
    }

    /**
     * Hak akses melihat daftar ticket type.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ticket_types.viewAny');
    }

    /**
     * Hak akses melihat detail ticket type.
     */
    public function view(User $user, TicketType $ticketType): bool
    {
        return $user->can('ticket_types.view');
    }

    /**
     * Hak akses membuat ticket type.
     */
    public function create(User $user): bool
    {
        return $user->can('ticket_types.create');
    }

    /**
     * Hak akses memperbarui ticket type.
     */
    public function update(User $user, TicketType $ticketType): bool
    {
        return $user->can('ticket_types.update');
    }

    /**
     * Hak akses menghapus ticket type.
     */
    public function delete(User $user, TicketType $ticketType): bool
    {
        return $user->can('ticket_types.delete');
    }
}
