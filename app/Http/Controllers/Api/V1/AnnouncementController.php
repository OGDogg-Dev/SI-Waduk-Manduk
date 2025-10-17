<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use App\Support\CacheTagger;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Date;

/**
 * Controller untuk pengumuman publik.
 */
class AnnouncementController
{
    /**
     * Daftar pengumuman yang sudah terbit.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 10), 50);
        $user = $request->user('sanctum');
        $isAdminView = $user && $user->can('announcements.viewAny');
        $cacheKey = 'announcements:'.($isAdminView ? 'admin:' : 'public:').md5($request->fullUrl());

        $paginator = CacheTagger::remember($cacheKey, ['announcements'], now()->addMinutes(5), function () use ($perPage, $isAdminView) {
            $query = Announcement::query()->with('media');

            if (! $isAdminView) {
                $now = Date::now();

                $query->whereNotNull('published_at')
                    ->where('published_at', '<=', $now)
                    ->where(function ($sub) use ($now) {
                        $sub->whereNull('expired_at')
                            ->orWhere('expired_at', '>', $now);
                    });
            }

            return $query
                ->orderByDesc('published_at')
                ->paginate($perPage)
                ->withQueryString();
        });

        return AnnouncementResource::collection($paginator);
    }
}
