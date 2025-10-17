<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Support\CacheTagger;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controller admin untuk Event.
 */
class EventController extends Controller
{
    /**
     * Daftarkan otorisasi resource.
     */
    public function __construct()
    {
        $this->authorizeResource(Event::class, 'event');
    }

    /**
     * Daftar event.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 15), 100);

        $paginator = Event::query()
            ->with('media')
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->input('q').'%'))
            ->orderByDesc('start_at')
            ->paginate($perPage)
            ->withQueryString();

        return EventResource::collection($paginator);
    }

    /**
     * Simpan event baru.
     */
    public function store(EventRequest $request)
    {
        $event = Event::create($request->validated());

        CacheTagger::flush(['events']);

        return (new EventResource($event))->response()->setStatusCode(201);
    }

    /**
     * Detail event.
     */
    public function show(Event $event): EventResource
    {
        return new EventResource($event->load('media'));
    }

    /**
     * Perbarui event.
     */
    public function update(EventRequest $request, Event $event): EventResource
    {
        $event->update($request->validated());

        CacheTagger::flush(['events']);

        return new EventResource($event->refresh()->load('media'));
    }

    /**
     * Hapus event.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        CacheTagger::flush(['events']);

        return response()->noContent();
    }
}
