<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\FacilityRequest;
use App\Http\Resources\FacilityResource;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

/**
 * Controller admin untuk Facility.
 */
class FacilityController extends Controller
{
    /**
     * Konstruktor otorisasi.
     */
    public function __construct()
    {
        $this->authorizeResource(Facility::class, 'facility');
    }

    /**
     * Daftar fasilitas.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 15), 100);

        $paginator = Facility::query()
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->input('q').'%'))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return FacilityResource::collection($paginator);
    }

    /**
     * Simpan fasilitas.
     */
    public function store(FacilityRequest $request)
    {
        $facility = Facility::create($request->validated());

        Cache::tags(['facilities'])->flush();

        return (new FacilityResource($facility))->response()->setStatusCode(201);
    }

    /**
     * Detail fasilitas.
     */
    public function show(Facility $facility): FacilityResource
    {
        return new FacilityResource($facility);
    }

    /**
     * Perbarui fasilitas.
     */
    public function update(FacilityRequest $request, Facility $facility): FacilityResource
    {
        $facility->update($request->validated());

        Cache::tags(['facilities'])->flush();

        return new FacilityResource($facility->refresh());
    }

    /**
     * Hapus fasilitas.
     */
    public function destroy(Facility $facility)
    {
        $facility->delete();

        Cache::tags(['facilities'])->flush();

        return response()->noContent();
    }
}
