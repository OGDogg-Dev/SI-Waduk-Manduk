<?php

namespace App\Policies;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy untuk pengelolaan Merchant.
 */
class MerchantPolicy
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
     * Mengizinkan melihat daftar merchant.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('merchants.viewAny');
    }

    /**
     * Mengizinkan melihat detail merchant.
     */
    public function view(User $user, Merchant $merchant): bool
    {
        return $user->can('merchants.view');
    }

    /**
     * Mengizinkan membuat merchant.
     */
    public function create(User $user): bool
    {
        return $user->can('merchants.create');
    }

    /**
     * Mengizinkan memperbarui merchant.
     */
    public function update(User $user, Merchant $merchant): bool
    {
        return $user->can('merchants.update');
    }

    /**
     * Mengizinkan menghapus merchant.
     */
    public function delete(User $user, Merchant $merchant): bool
    {
        return $user->can('merchants.delete');
    }
}
