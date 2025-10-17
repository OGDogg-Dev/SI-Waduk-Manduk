<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy untuk pengelolaan Inquiry.
 */
class InquiryPolicy
{
    use HandlesAuthorization;

    /**
     * Beri akses penuh pada super admin.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('super_admin') ? true : null;
    }

    /**
     * Izin melihat daftar inquiry.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('inquiries.viewAny');
    }

    /**
     * Izin melihat detail inquiry.
     */
    public function view(User $user, Inquiry $inquiry): bool
    {
        return $user->can('inquiries.view');
    }

    /**
     * Izin membuat inquiry internal.
     */
    public function create(User $user): bool
    {
        return $user->can('inquiries.create');
    }

    /**
     * Izin memperbarui inquiry atau statusnya.
     */
    public function update(User $user, Inquiry $inquiry): bool
    {
        return $user->can('inquiries.update');
    }

    /**
     * Izin menghapus inquiry.
     */
    public function delete(User $user, Inquiry $inquiry): bool
    {
        return $user->can('inquiries.delete');
    }
}
