<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AnnouncementRequest;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

/**
 * Controller admin untuk Announcement.
 */
class AnnouncementController extends Controller
{
    /**
     * Konstruktor otorisasi.
     */
    public function __construct()
    {
        $this->authorizeResource(Announcement::class, 'announcement');
    }

    /**
     * Daftar pengumuman.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 15), 100);

        $paginator = Announcement::query()
            ->with('media')
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->input('q').'%'))
            ->orderByDesc('published_at')
            ->paginate($perPage)
            ->withQueryString();

        return AnnouncementResource::collection($paginator);
    }

    /**
     * Simpan pengumuman.
     */
    public function store(AnnouncementRequest $request)
    {
        $announcement = Announcement::create($request->validated());

        Cache::tags(['announcements'])->flush();

        return (new AnnouncementResource($announcement))->response()->setStatusCode(201);
    }

    /**
     * Detail pengumuman.
     */
    public function show(Announcement $announcement): AnnouncementResource
    {
        return new AnnouncementResource($announcement->load('media'));
    }

    /**
     * Perbarui pengumuman.
     */
    public function update(AnnouncementRequest $request, Announcement $announcement): AnnouncementResource
    {
        $announcement->update($request->validated());

        Cache::tags(['announcements'])->flush();

        return new AnnouncementResource($announcement->refresh()->load('media'));
    }

    /**
     * Hapus pengumuman.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        Cache::tags(['announcements'])->flush();

        return response()->noContent();
    }
}
