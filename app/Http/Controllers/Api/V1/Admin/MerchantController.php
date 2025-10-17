<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\MerchantRequest;
use App\Http\Resources\MerchantResource;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

/**
 * Controller admin untuk Merchant.
 */
class MerchantController extends Controller
{
    /**
     * Konstruktor otorisasi.
     */
    public function __construct()
    {
        $this->authorizeResource(Merchant::class, 'merchant');
    }

    /**
     * Daftar merchant.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 15), 100);

        $paginator = Merchant::query()
            ->with('media')
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->input('q').'%'))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->input('category')))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return MerchantResource::collection($paginator);
    }

    /**
     * Simpan merchant baru.
     */
    public function store(MerchantRequest $request)
    {
        $merchant = Merchant::create($request->validated());

        Cache::tags(['merchants'])->flush();

        return (new MerchantResource($merchant))->response()->setStatusCode(201);
    }

    /**
     * Detail merchant.
     */
    public function show(Merchant $merchant): MerchantResource
    {
        return new MerchantResource($merchant->load('media'));
    }

    /**
     * Perbarui merchant.
     */
    public function update(MerchantRequest $request, Merchant $merchant): MerchantResource
    {
        $merchant->update($request->validated());

        Cache::tags(['merchants'])->flush();

        return new MerchantResource($merchant->refresh()->load('media'));
    }

    /**
     * Hapus merchant.
     */
    public function destroy(Merchant $merchant)
    {
        $merchant->delete();

        Cache::tags(['merchants'])->flush();

        return response()->noContent();
    }
}
