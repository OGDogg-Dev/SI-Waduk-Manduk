<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\TicketTypeResource;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

/**
 * Controller untuk tiket publik.
 */
class TicketTypeController
{
    /**
     * Daftar tiket aktif.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 10), 50);
        $user = $request->user('sanctum');
        $isAdminView = $user && $user->can('ticket_types.viewAny');
        $cacheKey = 'ticket-types:'.($isAdminView ? 'admin:' : 'public:').md5($request->fullUrl());

        $paginator = Cache::tags(['ticket-types'])->remember($cacheKey, now()->addMinutes(5), function () use ($perPage, $isAdminView) {
            return TicketType::query()
                ->when(! $isAdminView, fn ($query) => $query->where('is_active', true))
                ->orderBy('name')
                ->paginate($perPage)
                ->withQueryString();
        });

        return TicketTypeResource::collection($paginator);
    }
}
