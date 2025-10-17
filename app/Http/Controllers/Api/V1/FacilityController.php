<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\FacilityResource;
use App\Models\Facility;
use App\Support\CacheTagger;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controller fasilitas publik.
 */
class FacilityController
{
    /**
     * Daftar fasilitas tersedia.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user('sanctum');
        $isAdminView = $user && $user->can('facilities.viewAny');
        $cacheKey = 'facilities:'.($isAdminView ? 'admin:' : 'public:').md5($request->fullUrl());

        $collection = CacheTagger::remember($cacheKey, ['facilities'], now()->addMinutes(5), function () use ($isAdminView) {
            return Facility::query()
                ->when(! $isAdminView, fn ($query) => $query->where('is_available', true))
                ->orderBy('name')
                ->get();
        });

        return FacilityResource::collection($collection);
    }
}
