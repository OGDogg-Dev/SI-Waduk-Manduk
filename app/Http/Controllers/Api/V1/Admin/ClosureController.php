<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ClosureRequest;
use App\Http\Resources\ClosureResource;
use App\Models\Closure;
use App\Support\CacheTagger;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controller admin untuk Closure.
 */
class ClosureController extends Controller
{
    /**
     * Konstruktor otorisasi.
     */
    public function __construct()
    {
        $this->authorizeResource(Closure::class, 'closure');
    }

    /**
     * Daftar closure.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 15), 100);

        $paginator = Closure::query()
            ->with('attraction')
            ->orderByDesc('start_at')
            ->paginate($perPage)
            ->withQueryString();

        return ClosureResource::collection($paginator);
    }

    /**
     * Simpan closure baru.
     */
    public function store(ClosureRequest $request)
    {
        $closure = Closure::create($request->validated());

        CacheTagger::flush(['attractions']);

        return (new ClosureResource($closure->load('attraction')))->response()->setStatusCode(201);
    }

    /**
     * Detail closure.
     */
    public function show(Closure $closure): ClosureResource
    {
        return new ClosureResource($closure->load('attraction'));
    }

    /**
     * Perbarui closure.
     */
    public function update(ClosureRequest $request, Closure $closure): ClosureResource
    {
        $closure->update($request->validated());

        CacheTagger::flush(['attractions']);

        return new ClosureResource($closure->refresh()->load('attraction'));
    }

    /**
     * Hapus closure.
     */
    public function destroy(Closure $closure)
    {
        $closure->delete();

        CacheTagger::flush(['attractions']);

        return response()->noContent();
    }
}
