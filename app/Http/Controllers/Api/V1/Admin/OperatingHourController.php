<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\OperatingHourRequest;
use App\Http\Resources\OperatingHourResource;
use App\Models\OperatingHour;
use App\Support\CacheTagger;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controller admin untuk OperatingHour.
 */
class OperatingHourController extends Controller
{
    /**
     * Daftarkan policy resource.
     */
    public function __construct()
    {
        $this->authorizeResource(OperatingHour::class, 'operating_hour');
    }

    /**
     * List jam operasional.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 15), 100);

        $paginator = OperatingHour::query()
            ->with('attraction')
            ->when($request->filled('attraction_id'), fn ($query) => $query->where('attraction_id', $request->integer('attraction_id')))
            ->orderBy('day_of_week')
            ->paginate($perPage)
            ->withQueryString();

        return OperatingHourResource::collection($paginator);
    }

    /**
     * Simpan jam operasional.
     */
    public function store(OperatingHourRequest $request)
    {
        $operatingHour = OperatingHour::create($request->validated());

        CacheTagger::flush(['attractions']);

        return (new OperatingHourResource($operatingHour))->response()->setStatusCode(201);
    }

    /**
     * Detail jam operasional.
     */
    public function show(OperatingHour $operatingHour): OperatingHourResource
    {
        return new OperatingHourResource($operatingHour);
    }

    /**
     * Perbarui jam operasional.
     */
    public function update(OperatingHourRequest $request, OperatingHour $operatingHour): OperatingHourResource
    {
        $operatingHour->update($request->validated());

        CacheTagger::flush(['attractions']);

        return new OperatingHourResource($operatingHour->refresh());
    }

    /**
     * Hapus jam operasional.
     */
    public function destroy(OperatingHour $operatingHour)
    {
        $operatingHour->delete();

        CacheTagger::flush(['attractions']);

        return response()->noContent();
    }
}
