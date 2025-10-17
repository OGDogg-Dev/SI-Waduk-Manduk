<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AttractionRequest;
use App\Http\Resources\AttractionResource;
use App\Models\Attraction;
use App\Support\CacheTagger;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controller admin untuk mengelola Attraction.
 */
class AttractionController extends Controller
{
    /**
     * Konstruktor dengan otorisasi resource.
     */
    public function __construct()
    {
        $this->authorizeResource(Attraction::class, 'attraction');
    }

    /**
     * Listing attraction untuk admin.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 15), 100);

        $paginator = Attraction::query()
            ->with('media')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($sub) use ($request) {
                    $sub->where('name', 'like', '%'.$request->input('q').'%')
                        ->orWhere('description', 'like', '%'.$request->input('q').'%');
                });
            })
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->input('type')))
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return AttractionResource::collection($paginator);
    }

    /**
     * Simpan attraction baru.
     */
    public function store(AttractionRequest $request)
    {
        $attraction = Attraction::create($request->validated());

        CacheTagger::flush(['attractions']);

        return (new AttractionResource($attraction))->response()->setStatusCode(201);
    }

    /**
     * Detail attraction.
     */
    public function show(Attraction $attraction): AttractionResource
    {
        return new AttractionResource($attraction->load('media'));
    }

    /**
     * Perbarui data attraction.
     */
    public function update(AttractionRequest $request, Attraction $attraction): AttractionResource
    {
        $attraction->update($request->validated());

        CacheTagger::flush(['attractions']);

        return new AttractionResource($attraction->refresh()->load('media'));
    }

    /**
     * Hapus attraction.
     */
    public function destroy(Attraction $attraction)
    {
        $attraction->delete();

        CacheTagger::flush(['attractions']);

        return response()->noContent();
    }
}
