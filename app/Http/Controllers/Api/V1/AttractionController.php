<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\AttractionResource;
use App\Models\Attraction;
use App\Services\OperatingStatusService;
use App\Support\CacheTagger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controller attraction publik.
 */
class AttractionController
{
    /**
     * Daftar attraction aktif.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 10), 50);
        $user = $request->user('sanctum');
        $isAdminView = $user && $user->can('attractions.viewAny');
        $cacheKey = 'attractions:'.($isAdminView ? 'admin:' : 'public:').md5($request->fullUrl());

        /** @var LengthAwarePaginator $paginator */
        $paginator = CacheTagger::remember($cacheKey, ['attractions'], now()->addMinutes(5), function () use ($request, $perPage, $isAdminView) {
            return Attraction::query()
                ->with('media')
                ->when(! $isAdminView, fn ($query) => $query->where('is_active', true))
                ->when($request->filled('type'), fn ($query) => $query->where('type', $request->input('type')))
                ->when($request->filled('q'), function ($query) use ($request) {
                    $query->where(function ($sub) use ($request) {
                        $sub->where('name', 'like', '%'.$request->input('q').'%')
                            ->orWhere('description', 'like', '%'.$request->input('q').'%');
                    });
                })
                ->orderBy('name')
                ->paginate($perPage)
                ->withQueryString();
        });

        return AttractionResource::collection($paginator);
    }

    /**
     * Detail attraction berdasarkan slug.
     */
    public function show(string $slug, OperatingStatusService $service)
    {
        $cacheKey = 'attractions:show:'.$slug;

        $payload = CacheTagger::remember($cacheKey, ['attractions'], now()->addMinutes(5), function () use ($slug, $service) {
            $attraction = Attraction::query()->with('media')->where('slug', $slug)->firstOrFail();

            return [
                'data' => AttractionResource::make($attraction)->resolve(),
                'status' => $service->getStatusForToday($attraction->id),
            ];
        });

        return response()->json([
            'data' => $payload['data'],
            'status_today' => $payload['status'],
        ]);
    }
}
