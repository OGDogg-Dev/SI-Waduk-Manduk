<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TicketTypeRequest;
use App\Http\Resources\TicketTypeResource;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

/**
 * Controller admin untuk TicketType.
 */
class TicketTypeController extends Controller
{
    /**
     * Konstruktor otorisasi.
     */
    public function __construct()
    {
        $this->authorizeResource(TicketType::class, 'ticket_type');
    }

    /**
     * Daftar ticket type.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 15), 100);

        $paginator = TicketType::query()
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->input('q').'%'))
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return TicketTypeResource::collection($paginator);
    }

    /**
     * Simpan ticket type baru.
     */
    public function store(TicketTypeRequest $request)
    {
        $ticketType = TicketType::create($request->validated());

        Cache::tags(['ticket-types'])->flush();

        return (new TicketTypeResource($ticketType))->response()->setStatusCode(201);
    }

    /**
     * Detail ticket type.
     */
    public function show(TicketType $ticketType): TicketTypeResource
    {
        return new TicketTypeResource($ticketType);
    }

    /**
     * Perbarui ticket type.
     */
    public function update(TicketTypeRequest $request, TicketType $ticketType): TicketTypeResource
    {
        $ticketType->update($request->validated());

        Cache::tags(['ticket-types'])->flush();

        return new TicketTypeResource($ticketType->refresh());
    }

    /**
     * Hapus ticket type.
     */
    public function destroy(TicketType $ticketType)
    {
        $ticketType->delete();

        Cache::tags(['ticket-types'])->flush();

        return response()->noContent();
    }
}
