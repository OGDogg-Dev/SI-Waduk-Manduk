<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;

/**
 * Controller event publik.
 */
class EventController
{
    /**
     * Daftar event yang akan datang.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 10), 50);
        $user = $request->user('sanctum');
        $isAdminView = $user && $user->can('events.viewAny');
        $cacheKey = 'events:'.($isAdminView ? 'admin:' : 'public:').md5($request->fullUrl());

        $paginator = Cache::tags(['events'])->remember($cacheKey, now()->addMinutes(5), function () use ($request, $perPage, $isAdminView) {
            $query = Event::query()->with('media');

            if (! $isAdminView) {
                $now = Date::now();
                $query->where('is_published', true)
                    ->where('start_at', '>=', $now);
            }

            return $query
                ->when($request->filled('q'), function ($innerQuery) use ($request) {
                    $innerQuery->where(function ($sub) use ($request) {
                        $sub->where('title', 'like', '%'.$request->input('q').'%')
                            ->orWhere('description', 'like', '%'.$request->input('q').'%');
                    });
                })
                ->when($request->filled('date_from'), function ($innerQuery) use ($request) {
                    $innerQuery->whereDate('start_at', '>=', $request->input('date_from'));
                })
                ->when($request->filled('date_to'), function ($innerQuery) use ($request) {
                    $innerQuery->whereDate('start_at', '<=', $request->input('date_to'));
                })
                ->orderBy('start_at')
                ->paginate($perPage)
                ->withQueryString();
        });

        return EventResource::collection($paginator);
    }
}
