<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\MerchantResource;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

/**
 * Controller merchant publik.
 */
class MerchantController
{
    /**
     * Daftar merchant.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user('sanctum');
        $isAdminView = $user && $user->can('merchants.viewAny');
        $cacheKey = 'merchants:'.($isAdminView ? 'admin:' : 'public:').md5($request->fullUrl());

        $collection = Cache::tags(['merchants'])->remember($cacheKey, now()->addMinutes(5), function () use ($request, $isAdminView) {
            return Merchant::query()
                ->with('media')
                ->when(! $isAdminView, fn ($query) => $query->where('is_verified', true))
                ->when($request->filled('category'), fn ($query) => $query->where('category', $request->input('category')))
                ->when($request->filled('q'), function ($query) use ($request) {
                    $query->where(function ($sub) use ($request) {
                        $sub->where('name', 'like', '%'.$request->input('q').'%')
                            ->orWhere('location', 'like', '%'.$request->input('q').'%');
                    });
                })
                ->orderBy('name')
                ->get();
        });

        return MerchantResource::collection($collection);
    }
}
